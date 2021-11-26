<?php

declare(strict_types=1);

namespace App\Domain\User\Service;

use App\Domain\User\Repository\UserCreatorRepository;

final class UserCreator
{
    private UserCreatorRepository $repository;
    private UserDataValidator $validator;

    /**
     * Constructor.
     *
     * @param UserCreatorRepository $repository
     * @param UserDataValidator $validator
     */
    public function __construct(
        UserCreatorRepository $repository,
        UserDataValidator $validator
    ) {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    /**
     * Create a new user.
     *
     * @param array $data The form data
     *
     * @return int The new user ID
     */
    public function createUser(array $data): int
    {
        $this->validator->validate($data);
        return $this->repository->create($data);
    }

    /**
     * Create multiple users.
     *
     * @param array $data
     *
     * @return int Amount of created users.
     */
    public function createMultipleUsers(array $data): int
    {
        return $this->repository->createMultiple($data);
    }
}
