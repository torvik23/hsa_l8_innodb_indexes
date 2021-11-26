<?php

declare(strict_types=1);

namespace App\Domain\User\Service;

use App\Domain\Exception\ValidationException;
use App\Domain\User\Entity\User;
use App\Domain\User\Repository\UserReaderRepository;
use JetBrains\PhpStorm\Pure;

final class UserReader
{
    private UserReaderRepository $repository;

    /**
     * Constructor.
     *
     * @param UserReaderRepository $repository
     */
    public function __construct(UserReaderRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Read a user by the given field and field's value.
     *
     * @param string $fieldName
     * @param mixed|null $fieldValue
     *
     * @return User The user data
     */
    public function getUserDetails(string $fieldName, mixed $fieldValue = null): User
    {
        $userRow = $this->repository->getByField( $fieldName, $fieldValue);
        return $this->buildUser($userRow);
    }

    /**
     * Read all users.
     *
     * @throws ValidationException
     *
     * @return User[]
     */
    public function getUserList(): array
    {
        $userList = $this->repository->getList();
        $result = [];
        foreach ($userList as $userItem) {
            $result[] = $this->buildUser($userItem);
        }

        return $result;
    }

    /**
     * Build user instance from user data.
     *
     * @param array $userData
     *
     * @return User
     */
    private function buildUser(array $userData): User
    {
        $user = new User();
        $user->id = (int) $userData['id'];
        $user->email = (string) $userData['email'];
        $user->username = (string) $userData['username'];
        $user->firstName = (string) $userData['firstname'];
        $user->lastName = (string) $userData['lastname'];
        $user->dob = (string) $userData['dob'];
        $user->created = (string) $userData['created'];
        $user->modified = (string) $userData['modified'];

        return $user;
    }
}
