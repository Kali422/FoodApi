<?php

namespace App\Entity;

use App\Repository\CarbohydratesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CarbohydratesRepository::class)]
#[ORM\Table(name: 'carbohydrates_per_100g')]
class Carbohydrates
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $total;

    #[ORM\Column(nullable: true)]
    private ?float $sugars;

    public function __construct(float $total, ?float $sugars)
    {
        $this->total = $total;
        $this->sugars = $sugars;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(float $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function getSugars(): ?float
    {
        return $this->sugars;
    }

    public function setSugars(?float $sugars): self
    {
        $this->sugars = $sugars;

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
            'sugars' => $this->sugars
        ];
    }
}
