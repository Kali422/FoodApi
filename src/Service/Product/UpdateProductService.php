<?php

namespace App\Service\Product;

use App\Factory\ProductFactory;
use App\Repository\ProductRepository;
use App\Service\Exception\ProductNotFoundException;

class UpdateProductService
{
    private ProductFactory $productFactory;

    private ProductRepository $productRepository;

    public function __construct(
        ProductFactory $productFactory,
        ProductRepository $productRepository
    ) {
        $this->productFactory = $productFactory;
        $this->productRepository = $productRepository;
    }

    public function updateProduct(int $productId, array $rawData): void
    {
        $product = $this->productRepository->find($productId);
        if ($product === null) {
            throw new ProductNotFoundException("Product not found", 404);
        }

        $this->productFactory->update($product, $rawData);
        $this->productRepository->save($product, true);
    }
}