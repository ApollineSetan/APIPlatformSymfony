<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\GetCollection;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ApiResource(
    operations: [
        // Endpoint pour récupérer un produit par son ID
        new Get(
            uriTemplate: '/products/{id}', 
            requirements: ['id' => '\d+'],
        ),
        // Endpoint pour récupérer une collection de produits
        new GetCollection(
            uriTemplate: '/products',
        ),
        // Endpoint pour ajouter un produit
        new Post(
            uriTemplate: '/products',
            status: 201,
        ),
        // Endpoint pour supprimer un produit par son ID
        new Delete(
            uriTemplate: '/products/{id}',
            requirements: ['id' => '\d+'],
            status: 204,
        ),
        // Endpoint pour modifier un produit par son ID
        new Put(
            uriTemplate: '/products/{id}',
            requirements: ['id' => '\d+'],
            status: 201,
        ),
    ],
    order: ['id' => 'ASC', 'name' => 'ASC'],
    paginationEnabled: true
)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\Column]
    private ?float $weight = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function setWeight(float $weight): static
    {
        $this->weight = $weight;

        return $this;
    }
}
