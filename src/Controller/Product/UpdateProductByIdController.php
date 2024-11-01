<?php

declare(strict_types=1);

namespace App\Controller\Product;

use App\Command\UpdateProductByIdCommand;
use App\Command\UpdateProductByIdCommandHandler;
use App\Controller\Trait\SuccessResponseTrait;
use App\Entity\Product;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use App\Resolver\PatchUploadedFileResolver;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Attribute\MapUploadedFile;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Storage\StorageInterface;

#[AsController]
final readonly class UpdateProductByIdController
{
    use SuccessResponseTrait;

    public function __construct(
        private UpdateProductByIdCommandHandler $handler,
        private SerializerInterface $serializer,
        private StorageInterface $storage,
    ) {
    }

    #[Route('/products/{product}', name: 'app_product_update', methods: [Request::METHOD_POST])]
    #[OA\Response(
        response: 200,
        description: 'Returns the product',
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
    #[OA\Response(
        response: 422,
        description: 'Product data invalid',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(
                    property: 'error',
                    type: 'string',
                    example: 'Product not found.',
                ),
            ],
            type: 'object',
        )
    )]
    public function __invoke(
        Product $product,
        #[MapRequestPayload] UpdateProductByIdCommand $command,
        #[MapUploadedFile(
            constraints: [
                new Assert\File(mimeTypes: ['image/png', 'image/jpeg']),
            ],
            resolver: PatchUploadedFileResolver::class
        )] ?UploadedFile $imageFile = null,
    ): Response {
        $product = $this->handler->handle($product, $command, $imageFile);
        $product->setImage($this->storage->resolveUri($product, 'imageFile'));

        return $this->makeJsonResponse(
            data: $product,
            status: Response::HTTP_OK,
            context: ['groups' => 'product:get'],
        );
    }
}
