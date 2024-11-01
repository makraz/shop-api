<?php

declare(strict_types=1);

namespace App\Query;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final readonly class GetProductByIdQueryHandler
{
    public function __construct(
        private ProductRepository $productRepository,
    ) {
    }

    /**
     * @throws \Exception
     */
    public function handle(GetProductByIdQuery $query): Product
    {
        if (null === $product = $this->productRepository->find($query->id)) {
            throw new NotFoundHttpException('Product not found');
        }

        return $product;
    }
}
