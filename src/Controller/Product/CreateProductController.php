<?php

declare(strict_types=1);

namespace App\Controller\Product;

use App\Command\CreateProductCommand;
use App\Command\CreateProductCommandHandler;
use App\Controller\Trait\SuccessResponseTrait;
use App\Entity\Product;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
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
final readonly class CreateProductController
{
    use SuccessResponseTrait;

    public function __construct(
        private CreateProductCommandHandler $handler,
        private StorageInterface $storage,
        private SerializerInterface $serializer,
    ) {
    }

    #[Route('/products', name: 'app_product_create', methods: [Request::METHOD_POST])]
    #[OA\Response(
        response: 200,
        description: 'Returns the rewards of an user',
        content: new OA\JsonContent(
            ref: new Model(type: Product::class, groups: ['product:get']),
            type: 'object',
        )
    )]
    #[OA\Response(
        response: 422,
        description: 'Returns the rewards of an user',
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
    public function __invoke(
        #[MapRequestPayload] CreateProductCommand $command,
        #[MapUploadedFile([
            new Assert\File(mimeTypes: ['image/png', 'image/jpeg']),
        ])] UploadedFile $imageFile,
    ): Response {
        $product = $this->handler->handle($command, $imageFile);
        $product->setImage($this->storage->resolveUri($product, 'imageFile'));

        return $this->makeJsonResponse(
            data: $product,
            status: Response::HTTP_CREATED,
            context: ['groups' => 'product:get'],
        );
    }
}
