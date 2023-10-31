<?php

namespace App\Service\Product;

use App\Factory\ProductFactory;
use App\Repository\ProductRepository;

class AddProductService
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

    public function addProduct(array $rawData): void
    {
        $product = $this->productFactory->create($rawData);
        $this->productRepository->save($product, true);
    }
}