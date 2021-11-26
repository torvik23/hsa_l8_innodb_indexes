<?php

declare(strict_types=1);

namespace App\Application\Action;

use App\Domain\User\Service\UserGenerator;
use App\Domain\User\Service\UserReader;
use App\Responder\Responder;
use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class UserGenerateAction
{
    private UserGenerator $userGenerator;
    private UserReader $userReader;
    private Responder $responder;

    /**
     * @param UserGenerator $userGenerator
     * @param UserReader $userReader
     * @param Responder $responder
     */
    public function __construct(
        UserGenerator $userGenerator,
        UserReader $userReader,
        Responder $responder
    ) {
        $this->userGenerator = $userGenerator;
        $this->userReader = $userReader;
        $this->responder = $responder;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        try {
            $userId = $this->userGenerator->generate();
            $result = $this->userReader->getUserDetails('id', $userId);
            $response->withStatus(201);
        } catch (Exception $exception) {
            $result = [
                'error_message' => $exception->getMessage(),
                'error_code' => $exception->getCode()
            ];
            $response->withStatus(500);
        }

        return $this->responder->withJson($response, $result);
    }
}
