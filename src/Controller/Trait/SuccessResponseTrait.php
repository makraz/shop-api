<?php

declare(strict_types=1);

namespace App\Controller\Trait;

use Symfony\Component\HttpFoundation\Response;

trait SuccessResponseTrait
{
    public function makeJsonResponse(mixed $data = [], int $status = 200, string $format = 'json', array $headers = [], array $context = []): Response
    {
        $headers += ['Content-type' => 'application/json'] + $headers;
        return new Response(
            content: $this->serializer->serialize(
                data: $data,
                format: $format,
                context: $context,
            ),
            status: $status,
            headers: $headers,
        );
    }
}
