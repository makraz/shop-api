<?php

declare(strict_types=1);

namespace App\Resolver;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapUploadedFile;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

readonly class PatchUploadedFileResolver implements ValueResolverInterface
{
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $attribute = $argument->getAttributesOfType(MapUploadedFile::class,
            ArgumentMetadata::IS_INSTANCEOF)[0] ?? null;

        if (!$attribute) {
            return [];
        }

        $data = $request->files->all();
        if (empty($data)) {
            return [];
        }

        return $data;
    }
}
