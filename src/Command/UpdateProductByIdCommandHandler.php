<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\EntityInterface;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final readonly class UpdateProductByIdCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function handle(
        Product $product,
        UpdateProductByIdCommand $command,
        ?UploadedFile $uploadedFile = null,
    ): EntityInterface {
        $product = $command->hydrateToEntity($product, true);
        if ($uploadedFile) {
            $product->setImageFile($uploadedFile);
        }

        $this->entityManager->flush();

        return $product;
    }
}
