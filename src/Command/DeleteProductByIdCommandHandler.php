<?php

declare(strict_types=1);

namespace App\Command;

use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final readonly class DeleteProductByIdCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private ProductRepository $productRepository,
        private EntityManagerInterface $entityManager,
    )
    {
    }

    public function handle(DeleteProductByIdCommand $command): void
    {
        if (null === $product = $this->productRepository->find($command->id)) {
            throw new NotFoundHttpException('Product not found');
        }

        $this->entityManager->remove($product);
        $this->entityManager->flush();
    }
}
