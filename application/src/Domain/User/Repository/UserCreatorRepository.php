<?php

declare(strict_types=1);

namespace App\Domain\User\Repository;

use PDO;

final class UserCreatorRepository
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
     * Create user row.
     *
     * @param array $data
     *
     * @return int
     */
    public function create(array $data): int
    {
        $row = [
            'username' => $data['username'],
            'firstname' => $data['firstname'],
            'lastname' => $data['lastname'],
            'email' => $data['email'],
            'dob' => $data['dob'],
        ];

        $sql = "INSERT INTO user (username, email, firstname, lastname, dob)
                VALUES (:username, :email, :firstname, :lastname, :dob) 
                ON DUPLICATE KEY UPDATE
                    username=:username,
                    email=:email,
                    firstname=:firstname,
                    lastname=:lastname,
                    dob=:dob;";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute($row);

        return (int) $this->connection->lastInsertId();
    }

    /**
     * @param array $data
     *
     * @return int Returns number of affected rows.
     */
    public function createMultiple(array $data): int
    {
        $colNames = ['username', 'email', 'firstname', 'lastname', 'dob'];
        $dataToInsert = [];
        foreach ($data as $dataValue) {
            foreach($dataValue as $value) {
                $dataToInsert[] = $value;
            }
        }
        $updateCols = [];
        foreach ($colNames as $curCol) {
            $updateCols[] = $curCol . " = VALUES($curCol)";
        }

        $onDup = implode(', ', $updateCols);
        $rowPlaces = '(' . implode(', ', array_fill(0, count($colNames), '?')) . ')';
        $allPlaces = implode(', ', array_fill(0, count($data), $rowPlaces));

        $sql = sprintf(
            'INSERT INTO user (%s) VALUES %s ON DUPLICATE KEY UPDATE %s',
            implode(', ', $colNames),
            $allPlaces,
            $onDup
        );
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($dataToInsert);

        return (int) $stmt->rowCount();
    }
}
