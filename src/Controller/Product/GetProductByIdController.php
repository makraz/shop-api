<?php

declare(strict_types=1);

namespace App\Controller\Product;

use App\Controller\Trait\SuccessResponseTrait;
use App\Entity\Product;
use App\Query\GetProductByIdQuery;
use App\Query\GetProductByIdQueryHandler;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Vich\UploaderBundle\Storage\StorageInterface;

#[AsController]
final readonly class GetProductByIdController
{
    use SuccessResponseTrait;

    public function __construct(
        private GetProductByIdQueryHandler $handler,
        private StorageInterface $storage,
        private SerializerInterface $serializer,
    ) {
    }

    #[Route('/products/{productId}', methods: [Request::METHOD_GET])]
    #[OA\Response(
        response: 200,
        description: 'Returns the rewards of an user',
        content: new OA\JsonContent(
            ref: new Model(type: Product::class, groups: ['product:get']),
            type: 'object',
        )
    )]
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
        $product = $this->handler->handle(new GetProductByIdQuery($productId));
        $product->setImage($this->storage->resolveUri($product, 'imageFile'));

        return $this->makeJsonResponse(
            data: $product,
            status: Response::HTTP_CREATED,
            context: ['groups' => 'product:get'],
        );
    }
}
