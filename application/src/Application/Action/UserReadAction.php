<?php

declare(strict_types=1);

namespace App\Application\Action;

use App\Domain\User\Service\UserReader;
use App\Domain\User\Service\UserRequestParameterReader;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class UserReadAction
{
    /** @var UserReader */
    private UserReader $userReader;

    /** @var UserRequestParameterReader */
    private UserRequestParameterReader $requestParameterReader;

    /**
     * Constructor.
     *
     * @param UserReader $userReader
     * @param UserRequestParameterReader $requestParameterReader
     */
    public function __construct(UserReader $userReader, UserRequestParameterReader $requestParameterReader)
    {
        $this->userReader = $userReader;
        $this->requestParameterReader = $requestParameterReader;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args = []
    ): ResponseInterface {
        try {
            $parameterName = $this->requestParameterReader->getParameterName($args);
            $result = $this->userReader->getUserDetails($parameterName, $args[$parameterName] ?? '');
        } catch (\Exception $exception) {
            $result = [
                'error_message' => $exception->getMessage(),
                'error_code' => $exception->getCode()
            ];
        }
        $response->getBody()->write((string) json_encode($result));

        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
}
