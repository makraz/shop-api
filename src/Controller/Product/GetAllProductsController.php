<?php

declare(strict_types=1);

namespace App\Controller\Product;

use App\Entity\Product;
use App\Query\GetAllProductsQuery;
use App\Query\GetAllProductsQueryHandler;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Vich\UploaderBundle\Storage\StorageInterface;

#[AsController]
final readonly class GetAllProductsController
{
    public function __construct(
        private GetAllProductsQueryHandler $handler,
        private StorageInterface $storage,
        private SerializerInterface $serializer,
    ) {
    }

    #[Route('/products', methods: [Request::METHOD_GET])]
    #[OA\Response(
        response: 200,
        description: 'Returns the rewards of an user',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(
                ref: new Model(type: Product::class, groups: ['product:get']),
            ),
        )
    )]
    public function __invoke(): Response
    {
        $products = $this->handler->handle(new GetAllProductsQuery());

        array_map(fn (Product $product) => $product->setImage($this->storage->resolveUri($product, 'imageFile')),
            $products);

        return new Response(
            content: $this->serializer->serialize(
                data: $products,
                format: 'json',
                context: ['groups' => 'product:get'],
            ),
            status: Response::HTTP_OK,
            headers: [
                'Content-type' => 'application/json',
            ],
        );
    }
}
