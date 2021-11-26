<?php

declare(strict_types=1);

namespace App\Domain\User\Service;

use App\Domain\User\Entity\User;
use Faker\Factory;

final class UserGenerator
{
    /** @var UserCreator */
    private UserCreator $userCreator;

    /** @var Factory */
    private Factory $fakerFactory;

    /**
     * Constructor.
     *
     * @param UserCreator $userCreator
     * @param Factory $fakerFactory
     */
    public function __construct(
        UserCreator $userCreator,
        Factory $fakerFactory,
    ) {
        $this->userCreator = $userCreator;
        $this->fakerFactory = $fakerFactory;
    }

    /**
     * Generate user.
     *
     * @return int Returns generated user id.
     */
    public function generate(): int
    {
        return $this->userCreator->createUser($this->generateUserData());
    }

    /**
     * Generate user.
     *
     * @return int Returns number of created users.
     */
    public function generateMultiple(int $amountOfUsers = 1): int
    {
        $createdUsers = 0;
        $userList = [];
        for ($i = 0; $i < $amountOfUsers; $i ++) {
            $userList[] = $this->generateUserData();
            if (count($userList) === 10000) {
                $createdUsers += $this->userCreator->createMultipleUsers($userList);
                $userList = [];
            }
        }

        if (!empty($userList)) {
            $createdUsers += $this->userCreator->createMultipleUsers($userList);
        }

        return $createdUsers;
    }

    /**
     * @return array
     */
    private function generateUserData(): array
    {
        $faker = $this->fakerFactory::create();
        return [
            'username' => $faker->userName,
            'email' => $faker->email,
            'firstname' => $faker->firstName,
            'lastname' => $faker->lastName,
            'dob' => $faker->dateTimeBetween('1961-01-01', '2021-11-01')
                ->format('Y-m-d H:i:s'),
        ];
    }
}
