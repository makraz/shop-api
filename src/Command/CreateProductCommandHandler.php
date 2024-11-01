<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Serializer\SerializerInterface;

final readonly class CreateProductCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private SerializerInterface $serializer,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function handle(CreateProductCommand $command, UploadedFile $uploadedFile): Product
    {
        /** @var Product $product */
        $product = $this->serializer->denormalize($command, $command->getEntityClass(), null, [
            'disable_type_enforcement' => true,
            'collect_denormalization_errors' => true,
        ]);
        $product->setImageFile($uploadedFile);

        $this->entityManager->persist($product);
        $this->entityManager->flush();

        return $product;
    }
}
