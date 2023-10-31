<?php

namespace App\Entity;

use App\Repository\ProteinRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProteinRepository::class)]
#[ORM\Table(name: 'protein_per_100g')]
class Protein
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?float $total;

    #[ORM\Column(nullable: true)]
    private ?float $animalBased;

    #[ORM\Column(nullable: true)]
    private ?float $plantBased;

    public function __construct(float $total, ?float $animalBased, ?float $plantBased)
    {
        $this->total = $total;
        $this->animalBased = $animalBased;
        $this->plantBased = $plantBased;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(?float $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function getAnimalBased(): ?float
    {
        return $this->animalBased;
    }

    public function setAnimalBased(?float $animalBased): self
    {
        $this->animalBased = $animalBased;

        return $this;
    }

    public function getPlantBased(): ?float
    {
        return $this->plantBased;
    }

    public function setPlantBased(?float $plantBased): self
    {
        $this->plantBased = $plantBased;

        return $this;
    }

    public function __get(string $name)
    {
        return $this->$name;
    }

    public function __set(string $name, $value): void
    {
        $this->$name = $value;
    }

    public function toArray(): array
    {
        return [
            'total' => $this->total,
            'animalBased' => $this->animalBased,
            'plantBased' => $this->plantBased
        ];
    }
}
