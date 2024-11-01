<?php

declare(strict_types=1);

namespace App\Query;

use App\Entity\Product;
use App\Repository\ProductRepository;

final readonly class GetAllProductsQueryHandler
{
    public function __construct(
        private ProductRepository $productRepository,
    ) {
    }

    /**
     * @return Product[]
     */
    public function handle(GetAllProductsQuery $query): array
    {
        return $this->productRepository->findAll();
    }
}
