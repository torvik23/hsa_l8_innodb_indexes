<?php

declare(strict_types=1);

namespace App\Domain\User\Service;

use App\Domain\Exception\ValidationException;

final class UserDataValidator
{
    /**
     * @param array $data
     *
     * @return void
     */
    public function validate(array $data): void
    {
        $errors = [];

        if (empty($data['username'])) {
            $errors['username'] = 'Input required';
        }

        if (empty($data['email'])) {
            $errors['email'] = 'Input required';
        }

        if ($errors) {
            throw new ValidationException('Please check your input', $errors);
        }
    }
}
