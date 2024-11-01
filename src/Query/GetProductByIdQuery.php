<?php

declare(strict_types=1);

namespace App\Query;

final readonly class GetProductByIdQuery implements QueryInterface
{
    public function __construct(
        public int $id,
    ) {
    }
}