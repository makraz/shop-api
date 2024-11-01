<?php

declare(strict_types=1);

namespace App\Command;

use App\Command\Traits\HydrateCommandToEntityTrait;
use App\Entity\Product;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class UpdateProductByIdCommand implements CommandInterface
{
    use HydrateCommandToEntityTrait;

    public function __construct(
        public ?string $code = null,
        public ?string $name = null,
        public ?string $description = null,
        public ?string $image = null,
        public ?string $category = null,

        #[Assert\GreaterThan(0)]
        public ?float $price = null,

        #[Assert\GreaterThanOrEqual(0)]
        public ?int $quantity = null,
        public ?string $internalReference = null,
        public ?int $shellId = null,

        #[Assert\Choice(['INSTOCK', 'LOWSTOCK', 'OUTOFSTOCK'])]
        public ?string $inventoryStatus = null,
        public ?int $rating = null,
    ) {
    }

    public function getEntityClass(): string
    {
        return Product::class;
    }
}
