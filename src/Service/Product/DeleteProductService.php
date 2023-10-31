<?php

namespace App\Service\Product;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Service\Exception\ProductNotFoundException;

class DeleteProductService
{
    private ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function deleteProduct(int $productId): void
    {
        $product = $this->productRepository->find($productId);
        if ($product === null) {
            throw new ProductNotFoundException("Product not found", 404);
        }

        $this->productRepository->remove($product, true);
    }
}