<?php

namespace App\Entity;

use App\Repository\FatRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FatRepository::class)]
#[ORM\Table(name: 'fat_per_100g')]
class Fat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?float $total;

    #[ORM\Column(nullable: true)]
    private ?float $saturated;

    #[ORM\Column(nullable: true)]
    private ?float $monounsaturated;

    #[ORM\Column(nullable: true)]
    private ?float $omega3;

    #[ORM\Column(nullable: true)]
    private ?float $omega6;

    public function __construct(float $total, ?float $saturated, ?float $monounsaturated, ?float $omega3, ?float $omega6)
    {
        $this->total = $total;
        $this->saturated = $saturated;
        $this->monounsaturated = $monounsaturated;
        $this->omega3 = $omega3;
        $this->omega6 = $omega6;
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

    public function getSaturated(): ?float
    {
        return $this->saturated;
    }

    public function setSaturated(?float $saturated): self
    {
        $this->saturated = $saturated;

        return $this;
    }

    public function getMonounsaturated(): ?float
    {
        return $this->monounsaturated;
    }

    public function setMonounsaturated(?float $monounsaturated): self
    {
        $this->monounsaturated = $monounsaturated;

        return $this;
    }

    public function getOmega3(): ?float
    {
        return $this->omega3;
    }

    public function setOmega3(?float $omega3): self
    {
        $this->omega3 = $omega3;

        return $this;
    }

    public function getOmega6(): ?float
    {
        return $this->omega6;
    }

    public function setOmega6(?float $omega6): self
    {
        $this->omega6 = $omega6;

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
            'saturated' => $this->saturated,
            'monounsaturated' => $this->monounsaturated,
            'polyunsaturated' => [
                'omega-3' => $this->omega3,
                'omega-6' => $this->omega6
            ]
        ];
    }
}
