<?php

namespace App\Command;

use App\Entity\Categorie;
use App\Entity\Marque;
use App\Entity\Produit;
use App\Entity\Tag;
use App\Repository\CategorieRepository;
use App\Repository\MarqueRepository;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsCommand(
    name: 'app:import-produits',
    description: 'Importe le catalogue DummyJSON en base',
)]
class ImportProduitsCommand
{
    private const URL = 'https://dummyjson.com/products?limit=0&select=id,title,description,category,price,discountPercentage,rating,stock,tags,brand,sku,minimumOrderQuantity,thumbnail';

    // mémoire de l'import : avant le flush, une entité créée n'est pas retrouvable en base
    private array $categories = [];
    private array $marques = [];
    private array $tags = [];

    public function __construct(
        private HttpClientInterface $client,
        private EntityManagerInterface $em,
        private CategorieRepository $categorieRepository,
        private MarqueRepository $marqueRepository,
        private TagRepository $tagRepository,
    ) {
    }

    public function __invoke(SymfonyStyle $io): int
    {
        // E - EXTRAIRE
        // un seul appel, tout le catalogue est dans la clé "products"
        $data = $this->client->request('GET', self::URL)->toArray();
        $produits = $data['products'] ?? [];

        // ids déjà en base, chargés une fois pour la règle 2
        $lignes = $this->em
            ->createQuery('SELECT p.dummyJsonId FROM App\Entity\Produit p')
            ->getScalarResult();
        $dejaEnBase = array_flip(array_column($lignes, 'dummyJsonId'));

        $importes = 0;
        $ignores = 0;

        foreach ($produits as $p) {
            // T - règle 1
            // sans titre, sans catégorie ou sans prix > 0 : on ignore
            $titre = trim((string) ($p['title'] ?? ''));
            $categorieNom = mb_strtolower(trim((string) ($p['category'] ?? '')));
            $prix = $p['price'] ?? null;

            if ($titre === '' || $categorieNom === '' || !is_numeric($prix) || $prix <= 0) {
                $ignores++;
                continue;
            }

            // T - règle 2
            // clé naturelle déjà en base : on ignore (l'import peut être relancé)
            $id = $p['id'] ?? null;
            if ($id === null || isset($dejaEnBase[$id])) {
                $ignores++;
                continue;
            }

            $produit = new Produit();

            // T - mapping
            // troncatures (règle 6) et valeurs absentes (règle 7)
            $produit->setDummyJsonId((int) $id);
            $produit->setTitre(mb_substr($titre, 0, 255));
            $produit->setDescription(isset($p['description']) ? mb_substr((string) $p['description'], 0, 2000) : null);
            $produit->setPrix((float) $prix);
            $produit->setRemise(isset($p['discountPercentage']) ? (float) $p['discountPercentage'] : null);
            $produit->setNote(isset($p['rating']) ? (float) $p['rating'] : null);
            $produit->setStock((int) ($p['stock'] ?? 0));
            $produit->setSku(isset($p['sku']) ? mb_substr((string) $p['sku'], 0, 50) : null);
            $produit->setQuantiteMinimale(isset($p['minimumOrderQuantity']) ? (int) $p['minimumOrderQuantity'] : null);
            $produit->setMiniatureUrl(isset($p['thumbnail']) ? mb_substr((string) $p['thumbnail'], 0, 255) : null);

            // T - règle 3
            // catégorie, marque et tags : retrouvés ou créés
            $produit->setCategorie($this->trouverOuCreerCategorie($categorieNom));

            $marqueNom = trim((string) ($p['brand'] ?? ''));
            if ($marqueNom !== '') {
                $produit->setMarque($this->trouverOuCreerMarque($marqueNom));
            }

            // les clés du tableau retirent les doublons du produit
            $nomsTags = [];
            foreach ($p['tags'] ?? [] as $tagBrut) {
                $nomTag = mb_strtolower(trim((string) $tagBrut));
                if ($nomTag !== '') {
                    $nomsTags[$nomTag] = true;
                }
            }
            foreach (array_keys($nomsTags) as $nomTag) {
                $produit->addTag($this->trouverOuCreerTag((string) $nomTag));
            }

            // L - CHARGER
            // persist ici, un seul flush à la fin
            $this->em->persist($produit);
            $dejaEnBase[$id] = true;
            $importes++;
        }

        // L - CHARGER
        $this->em->flush();

        $io->success(sprintf('%d produits importés, %d ignorés.', $importes, $ignores));

        return Command::SUCCESS;
    }

    private function trouverOuCreerCategorie(string $nom): Categorie
    {
        if (isset($this->categories[$nom])) {
            return $this->categories[$nom];
        }

        $categorie = $this->categorieRepository->findOneBy(['nom' => $nom]);
        if ($categorie === null) {
            $categorie = new Categorie();
            $categorie->setNom($nom);
            $this->em->persist($categorie);
        }

        return $this->categories[$nom] = $categorie;
    }

    private function trouverOuCreerMarque(string $nom): Marque
    {
        if (isset($this->marques[$nom])) {
            return $this->marques[$nom];
        }

        $marque = $this->marqueRepository->findOneBy(['nom' => $nom]);
        if ($marque === null) {
            $marque = new Marque();
            $marque->setNom($nom);
            $this->em->persist($marque);
        }

        return $this->marques[$nom] = $marque;
    }

    private function trouverOuCreerTag(string $nom): Tag
    {
        if (isset($this->tags[$nom])) {
            return $this->tags[$nom];
        }

        $tag = $this->tagRepository->findOneBy(['nom' => $nom]);
        if ($tag === null) {
            $tag = new Tag();
            $tag->setNom($nom);
            $this->em->persist($tag);
        }

        return $this->tags[$nom] = $tag;
    }
}