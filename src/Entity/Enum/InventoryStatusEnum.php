<?php

declare(strict_types=1);

namespace App\Entity\Enum;

enum InventoryStatusEnum: string
{
    case INSTOCK = 'INSTOCK';
    case LOWSTOCK = 'LOWSTOCK';
    case OUTOFSTOCK = 'OUTOFSTOCK';
}
