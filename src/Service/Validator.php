<?php 

namespace App\Service;

final class Validator
{
    /** @var string[] */
    private array $errors = [];

    public function __construct(readonly string $name, readonly string $email, readonly string $phone)
    {
        if (empty($name)) {
            $this->errors[] = "Veuillez renseigner le nom.";
        }

        if (empty($email)) {
            $this->errors[] = "Veuillez renseigner l'adresse email.";
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = "Veuillez renseigner une adresse email valide.";
        }

        if (empty($phone)) {
            $this->errors[] = "Veuillez renseigner le numéro de téléphone.";
        } else if (!preg_match("#^\+?[1-9]\d{1,14}$#", $phone)) {
            $this->errors[] = "Veuillez renseigner un numéro de téléphone valide.";
        }
    }

    /** @return string[] */
    public function getErrors(): array
    {
        return $this->errors;
    }

    public function isValid(): bool
    {
        return sizeof($this->getErrors()) > 0 ? false : true;
    }
}