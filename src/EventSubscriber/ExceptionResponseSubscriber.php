<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Kernel;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\GoneHttpException;
use Symfony\Component\HttpKernel\Exception\LengthRequiredHttpException;
use Symfony\Component\HttpKernel\Exception\LockedHttpException;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\PreconditionFailedHttpException;
use Symfony\Component\HttpKernel\Exception\PreconditionRequiredHttpException;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\HttpKernel\Exception\UnsupportedMediaTypeHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Validator\Exception\ValidationFailedException;

readonly class ExceptionResponseSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private Kernel $kernel,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::EXCEPTION => ['__invoke']];
    }

    public function __invoke(ExceptionEvent $event): void
    {
        $throwable = $event->getThrowable();

        if ($throwable instanceof UnprocessableEntityHttpException) {
            $exception = $throwable->getPrevious();
            $content = [];
            foreach ($exception->getViolations() as $violation) {
                $content['error']['message'] = $violation->getMessage();
                $content['error']['propertyPath'] = $violation->getPropertyPath();
            }
        } else {
            $content['error'] = $throwable->getMessage();
        }

        if ($this->kernel->isDebug()) {
            $content['stack_trace'] = $throwable->getTrace();
        }

        $response = new JsonResponse(
            data: $content,
            status: $this->getCodeStatusFromException($throwable::class),
            headers: [
                'Content-Type' => 'application/json',
            ],
        );

        $event->setResponse($response);
    }

    private function getCodeStatusFromException(string $exceptionNamespace): int
    {
        return match ($exceptionNamespace) {
            BadRequestHttpException::class => 400,
            AccessDeniedHttpException::class => 403,
            NotFoundHttpException::class => 404,
            NotAcceptableHttpException::class => 406,
            ConflictHttpException::class => 409,
            GoneHttpException::class => 410,
            LengthRequiredHttpException::class => 411,
            PreconditionFailedHttpException::class => 412,
            LockedHttpException::class => 423,
            UnsupportedMediaTypeHttpException::class => 415,
            UnprocessableEntityHttpException::class => 422,
            PreconditionRequiredHttpException::class => 428,
            TooManyRequestsHttpException::class => 429,
            ServiceUnavailableHttpException::class => 503,
            default => 500,
        };
    }
}
