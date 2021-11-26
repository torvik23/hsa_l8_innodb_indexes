<?php

declare(strict_types=1);

namespace App\Domain\User\Service;

use App\Domain\Exception\ValidationException;

final class UserRequestParameterReader
{
    private const USER_PARAMETERS = [
        'id',
        'username',
        'dob'
    ];

    /**
     * @param array $parameters
     *
     * @return string
     */
    public function getParameterName(array $parameters): string
    {
        $parameter = '';
        foreach ($parameters as $parameterName) {
            if (in_array($parameterName, self::USER_PARAMETERS)) {
                $parameter = $parameterName;
                break;
            }
        }

        if (empty($parameter)) {
            throw new ValidationException('Please check your query', [
                "Requested parameter is empty or not allowed"
            ]);
        }

        return $parameter;
    }
}
