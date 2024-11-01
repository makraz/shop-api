<?php

declare(strict_types=1);

namespace App\Command;

use App\Command\Traits\HydrateCommandToEntityTrait;
use App\Entity\Product;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class CreateProductCommand implements CommandInterface
{
    use HydrateCommandToEntityTrait;

    public function __construct(
        public string $code,
        public string $name,
        public string $description,
        public string $category,
        public float $price,
        #[Assert\GreaterThanOrEqual(0)]
        public int $quantity,
        public string $internalReference,
        public int $shellId,
        #[Assert\Choice(['INSTOCK', 'LOWSTOCK', 'OUTOFSTOCK'])]
        public string $inventoryStatus,
        public int $rating,
    ) {
    }

    public function getEntityClass(): string
    {
        return Product::class;
    }
}
