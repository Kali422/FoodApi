<?php

namespace App\Factory;

use App\Entity\Carbohydrates;
use App\Entity\Fat;
use App\Entity\Product;
use App\Entity\Protein;
use App\Service\Exception\ProductValidationFailedException;
use App\Service\Product\ProductValidationService;

class ProductFactory
{
    private ProductValidationService $productValidationService;

    public function __construct(ProductValidationService $productValidationService)
    {
        $this->productValidationService = $productValidationService;
    }

    public function create(array $rawData): Product
    {
        $validationErrors = $this->productValidationService->validateAdd($rawData);
        if (false === empty($validationErrors)) {
            throw new ProductValidationFailedException(
                "Add product body validation failed",
                412,
                null,
                $validationErrors
            );
        }

        $carbohydrates = new Carbohydrates(
            $rawData['carbohydrates']['total'],
            $rawData['carbohydrates']['sugars'] ?? null
        );

        $fat = new Fat(
            $rawData['fat']['total'],
            $rawData['fat']['saturated'] ?? null,
            $rawData['fat']['monounsaturated'] ?? null,
            $rawData['fat']['omega3'] ?? null,
            $rawData['fat']['omega6'] ?? null
        );

        $protein = new Protein(
            $rawData['protein']['total'],
            $rawData['protein']['animalBased'] ?? null,
            $rawData['protein']['plantBased'] ?? null
        );

        return new Product(htmlspecialchars($rawData['name']), $rawData['kcal'], $carbohydrates, $fat, $protein);
    }

    public function update(Product $product, array $rawData): Product
    {
        $errors = $this->productValidationService->validateUpdate($rawData);
        if (false === empty($errors)) {
            throw new ProductValidationFailedException('Update product body validation failed', 412, null, $errors);
        }

        foreach ($rawData as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $subKey => $subValue) {
                    $product->$key->$subKey = $subValue;
                }
            } else {
                $product->$key = $value;
            }
        }

        return $product;
    }
}