<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name;

    private int $grammage = 100;

    #[ORM\Column]
    private ?float $kcal;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Carbohydrates $carbohydrates;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Fat $fat;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Protein $protein;

    public function __construct(
        string $name,
        float $kcal,
        Carbohydrates $carbohydrates,
        Fat $fat,
        Protein $protein,
        int $grammage = 100
    ) {
        $this->name = $name;
        $this->kcal = $kcal;
        $this->carbohydrates = $carbohydrates;
        $this->fat = $fat;
        $this->protein = $protein;
        $this->grammage = $grammage;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getGrammage(): int
    {
        return $this->grammage;
    }

    public function setGrammage(int $grammage): void
    {
        $this->grammage = $grammage;
    }

    public function getKcal(): ?float
    {
        return $this->kcal;
    }

    public function setKcal(float $kcal): self
    {
        $this->kcal = $kcal;

        return $this;
    }

    public function getCarbohydrates(): ?Carbohydrates
    {
        return $this->carbohydrates;
    }

    public function setCarbohydrates(Carbohydrates $carbohydrates): self
    {
        $this->carbohydrates = $carbohydrates;

        return $this;
    }

    public function getFat(): ?Fat
    {
        return $this->fat;
    }

    public function setFat(Fat $fat): self
    {
        $this->fat = $fat;

        return $this;
    }

    public function getProtein(): ?Protein
    {
        return $this->protein;
    }

    public function setProtein(Protein $protein): self
    {
        $this->protein = $protein;

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
            'product' => [
                'id' => $this->id,
                'name' => $this->name,
                'grammage' => $this->grammage,
                'kcal' => $this->kcal,
                'carbohydrates' => $this->carbohydrates->toArray(),
                'fat' => $this->fat->toArray(),
                'protein' => $this->protein->toArray()
            ]
        ];
    }
}
