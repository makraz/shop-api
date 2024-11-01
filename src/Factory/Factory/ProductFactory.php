<?php

declare(strict_types=1);

namespace App\Factory\Factory;

use App\Entity\Enum\InventoryStatusEnum;
use App\Entity\Product;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

final class ProductFactory extends PersistentProxyObjectFactory
{
    public static function class(): string
    {
        return Product::class;
    }

    protected function defaults(): array|callable
    {
        return [
            'category' => self::faker()->text(255),
            'code' => self::faker()->text(255),
            'description' => self::faker()->text(255),
            'internalReference' => self::faker()->randomNumber(),
            'inventoryStatus' => self::faker()->randomElement(InventoryStatusEnum::cases()),
            'name' => self::faker()->text(255),
            'price' => self::faker()->randomFloat(),
            'quantity' => self::faker()->randomNumber(),
            'rating' => self::faker()->randomNumber(),
            'shellId' => self::faker()->randomNumber(),
            'createdAt' => new \DateTime(),
            'updatedAt' => new \DateTime(),
        ];
    }

    protected function initialize(): static
    {
        return $this;
    }
}
