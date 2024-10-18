<?php

namespace App\Factory;

use App\Service\Validator;

class ValidatorFactory
{
    public static function make(string $name, string $email, string $phone): Validator
    {
        return new Validator($name, $email, $phone);
    }
}