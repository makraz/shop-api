<?php

declare(strict_types=1);

namespace App\Controller\Product;

use App\Command\DeleteProductByIdCommand;
use App\Command\DeleteProductByIdCommandHandler;
use App\Controller\Trait\SuccessResponseTrait;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[AsController]
final readonly class DeleteProductByIdController
{
    use SuccessResponseTrait;

    public function __construct(
        private DeleteProductByIdCommandHandler $handler,
        private SerializerInterface $serializer,
    ) {
    }

    #[Route('/products/{productId}', methods: [Request::METHOD_DELETE])]
    #[OA\Response(
        response: 404,
        description: 'Product not found',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(
                    property: 'message',
                    type: 'string',
                    example: 'This value should be greater than or equal to 0.',
                ),
                new OA\Property(
                    property: 'propertyPath',
                    type: 'string',
                    example: 'quantity',
                ),
            ],
            type: 'object',
        )
    )]
    public function __invoke(int $productId): Response
    {
        $this->handler->handle(new DeleteProductByIdCommand($productId));

        return $this->makeJsonResponse(
            status: Response::HTTP_OK,
        );
    }
}
