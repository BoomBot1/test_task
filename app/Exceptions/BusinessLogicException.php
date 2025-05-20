<?php

namespace App\Exceptions;

use App\Support\Helpers\Boilerplate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use RuntimeException;
use Throwable;

class BusinessLogicException extends RuntimeException
{
    protected array $debug = [];

    protected $code = 500;

    protected int $statusCode = 500;

    public function __construct(
        string $message = '',
        int $statusCode = 500,
        array $debug = [],
        ?Throwable $previous = null
    ) {
        $this->debug = $debug;
        $this->statusCode = $statusCode;
        $this->code = $statusCode;
        $message = $message ?: $this->getDefaultMessage();

        parent::__construct(message: $message, code: $this->code, previous: $previous);
    }

    public function setDebug(array $debug): void
    {
        $this->debug = $debug;
    }

    public function setCode(int $code): void
    {
        $this->code = $code;
    }

    public function setStatusCode(int $statusCode): static
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getDebug(): array
    {
        return $this->debug;
    }

    protected function getDefaultMessage(): string
    {
        return class_basename($this);
    }

    public function render(Request $request): ?JsonResponse
    {
        if ((new Boilerplate)->requestWantsJson($request)) {
            return response()->json([
                'message' => $this->getMessage(),
                'data' => $this->getData(),
            ], $this->statusCode);
        }

        return null;
    }

    private function getData(): array
    {
        return App::hasDebugModeEnabled()
            ? [
                'debug' => $this->getDebug(),
                'trace' => $this->getTrace(),
            ]
            : [];
    }
}
