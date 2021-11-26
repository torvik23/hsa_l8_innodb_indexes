<?php

declare(strict_types=1);

namespace App\Domain\User\Repository;

use DomainException;
use PDO;

final class UserReaderRepository
{
    /**
     * The database connection.
     *
     * @var PDO
     */
    private PDO $connection;

    /**
     * Constructor.
     *
     * @param PDO $connection
     */
    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Get user by the given user id.
     *
     * @param string $fieldName
     * @param mixed|null $fieldValue
     *
     * @return array The user row
     */
    public function getByField(string $fieldName, mixed $fieldValue = null): array
    {
        $sql = sprintf(
            "SELECT id, username, email, firstname, lastname, dob, created, modified FROM user WHERE %s = :%s;",
            $fieldName,
            $fieldName
        );
        $statement = $this->connection->prepare($sql);
        $statement->execute([$fieldName => $fieldValue]);

        $row = $statement->fetch();

        if (!$row) {
            throw new DomainException('User not found');
        }

        return $row;
    }

    /**
     * Get user list.
     *
     * @throws DomainException
     *
     * @return array
     */
    public function getList(): array
    {
        $sql = "SELECT id, username, email, firstname, lastname, dob, created, modified FROM user;";
        $statement = $this->connection->prepare($sql);
        $statement->execute();

        $data = $statement->fetchAll();
        if (!$data) {
            throw new DomainException('Users are not found');
        }

        return $data;
    }
}
