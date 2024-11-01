<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Product;

final readonly class DeleteProductByIdCommand implements CommandInterface
{
    public function __construct(
        public int $id,
    ) {
    }

    public function getEntityClass(): string
    {
        return Product::class;
    }
}
