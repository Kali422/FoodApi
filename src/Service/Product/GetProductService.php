<?php

namespace App\Service\Product;

use App\Entity\Carbohydrates;
use App\Entity\Fat;
use App\Entity\Product;
use App\Entity\Protein;
use App\Repository\ProductRepository;
use App\Service\Exception\ProductNotFoundException;

class GetProductService
{
    private ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function getProduct(int $id): Product
    {
        $product = $this->productRepository->find($id);
        if ($product === null) {
            throw new ProductNotFoundException("Product not found", 404);
        }
        return $product;
    }

    public function getProductPerGrammage(int $id, int $grammage): Product
    {
        $product = $this->productRepository->find($id);
        if ($product === null) {
            throw new ProductNotFoundException("Product not found", 404);
        }
        return $this->recalculateProduct($product, $grammage);
    }

    private function recalculateProduct(Product $product, int $grammage): Product
    {
        $ratio = $grammage / $product->getGrammage();

        $product->setGrammage($grammage);
        $product->setKcal(round($product->getKcal() * $ratio, 2));

        $carbohydrates = $this->recalculateCarbohydrates($product->getCarbohydrates(), $ratio);
        $product->setCarbohydrates($carbohydrates);

        $fat = $this->recalculateFat($product->getFat(), $ratio);
        $product->setFat($fat);

        $protein = $this->recalculateProtein($product->getProtein(), $ratio);
        $product->setProtein($protein);

        return $product;
    }

    private function recalculateCarbohydrates(Carbohydrates $carbohydrates, float $ratio): Carbohydrates
    {
        $carbohydrates->setTotal(round($carbohydrates->getTotal() * $ratio, 2));
            $carbohydrates->getSugars() !== null ?? $carbohydrates->setSugars(
            round($carbohydrates->getSugars() * $ratio, 2)
        );
        return $carbohydrates;
    }

    private function recalculateFat(Fat $fat, float $ratio): Fat
    {
        $fat->setTotal(round($fat->getTotal() * $ratio, 2));
            $fat->getSaturated() !== null ?? $fat->setSaturated(round($fat->getSaturated() * $ratio, 2));
            $fat->getMonounsaturated() !== null ?? $fat->setMonounsaturated(
            round($fat->getMonounsaturated() * $ratio, 2)
        );
            $fat->getOmega3() !== null ?? $fat->setOmega3(round($fat->getOmega3() * $ratio, 2));
            $fat->getOmega6() !== null ?? $fat->setOmega6(round($fat->getOmega6() * $ratio, 2));
        return $fat;
    }

    private function recalculateProtein(Protein $protein, float $ratio): Protein
    {
        $protein->setTotal(round($protein->getTotal() * $ratio, 2));
            $protein->getPlantBased() !== null ?? $protein->setPlantBased(round($protein->getPlantBased() * $ratio, 2));
            $protein->getAnimalBased() !== null ?? $protein->setAnimalBased(
            round($protein->getAnimalBased() * $ratio, 2)
        );
        return $protein;
    }
}