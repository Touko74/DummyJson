<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ProduitRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
#[ApiResource]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $dummyJsonId = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column]
    private ?float $prix = null;

    #[ORM\Column]
    private ?float $remise = null;

    #[ORM\Column(nullable: true)]
    private ?float $note = null;

    #[ORM\Column]
    private ?int $stock = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $sku = null;

    #[ORM\Column(nullable: true)]
    private ?int $quantiteMinimale = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $miniatureUrl = null;

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

    public function setRemise(float $remise): static
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
}
