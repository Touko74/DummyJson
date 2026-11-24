<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['produit:lire']],
    denormalizationContext: ['groups' => ['produit:ecrire']],
)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // clé naturelle, sans groupe : invisible et jamais écrite par l'API
    #[ORM\Column(unique: true)]
    private ?int $dummyJsonId = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    #[Groups(['produit:lire', 'produit:ecrire'])]
    private ?string $titre = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\Length(max: 2000)]
    #[Groups(['produit:lire', 'produit:ecrire'])]
    private ?string $description = null;

    #[ORM\Column]
    #[Assert\NotNull]
    #[Assert\Positive]
    #[Groups(['produit:lire', 'produit:ecrire'])]
    private ?float $prix = null;

    #[ORM\Column(nullable: true)]
    #[Assert\Range(min: 0, max: 100)]
    #[Groups(['produit:lire', 'produit:ecrire'])]
    private ?float $remise = null;

    #[ORM\Column(nullable: true)]
    #[Assert\Range(min: 0, max: 5)]
    #[Groups(['produit:lire', 'produit:ecrire'])]
    private ?float $note = null;

    #[ORM\Column]
    #[Assert\PositiveOrZero]
    #[Groups(['produit:lire', 'produit:ecrire'])]
    private ?int $stock = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Assert\Length(max: 50)]
    #[Groups(['produit:lire', 'produit:ecrire'])]
    private ?string $sku = null;

    #[ORM\Column(nullable: true)]
    #[Assert\Positive]
    #[Groups(['produit:lire', 'produit:ecrire'])]
    private ?int $quantiteMinimale = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Url]
    #[Assert\Length(max: 255)]
    #[Groups(['produit:lire', 'produit:ecrire'])]
    private ?string $miniatureUrl = null;

    #[ORM\ManyToOne(inversedBy: 'produits')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['produit:lire', 'produit:ecrire'])]
    private ?Categorie $categorie = null;

    #[ORM\ManyToOne(inversedBy: 'produits')]
    #[Groups(['produit:lire', 'produit:ecrire'])]
    private ?Marque $marque = null;

    /**
     * @var Collection<int, Tag>
     */
    #[ORM\ManyToMany(targetEntity: Tag::class, inversedBy: 'produits')]
    #[Groups(['produit:lire', 'produit:ecrire'])]
    private Collection $tags;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDummyJsonId(): ?int
    {
        return $this->dummyJsonId;
    }

    public function setDummyJsonId(int $dummyJsonId): static
    {
        $this->dummyJsonId = $dummyJsonId;

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getRemise(): ?float
    {
        return $this->remise;
    }

    public function setRemise(?float $remise): static
    {
        $this->remise = $remise;

        return $this;
    }

    public function getNote(): ?float
    {
        return $this->note;
    }

    public function setNote(?float $note): static
    {
        $this->note = $note;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): static
    {
        $this->stock = $stock;

        return $this;
    }

    public function getSku(): ?string
    {
        return $this->sku;
    }

    public function setSku(?string $sku): static
    {
        $this->sku = $sku;

        return $this;
    }

    public function getQuantiteMinimale(): ?int
    {
        return $this->quantiteMinimale;
    }

    public function setQuantiteMinimale(?int $quantiteMinimale): static
    {
        $this->quantiteMinimale = $quantiteMinimale;

        return $this;
    }

    public function getMiniatureUrl(): ?string
    {
        return $this->miniatureUrl;
    }

    public function setMiniatureUrl(?string $miniatureUrl): static
    {
        $this->miniatureUrl = $miniatureUrl;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): static
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getMarque(): ?Marque
    {
        return $this->marque;
    }

    public function setMarque(?Marque $marque): static
    {
        $this->marque = $marque;

        return $this;
    }

    /**
     * @return Collection<int, Tag>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): static
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
        }

        return $this;
    }

    public function removeTag(Tag $tag): static
    {
        $this->tags->removeElement($tag);

        return $this;
    }
}
