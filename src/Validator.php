<?php

namespace App\Core;

class Validator
{
    private function __construct(){}

    public static function required(mixed $value, string $key, array &$errors, string $message = 'Ce champ est obligatoire'): bool
    {
        if ($value === null || trim((string) $value) === '') {
            $errors[$key] = $message;
            return false;
        }
        return true;
    }

    public static function unique(mixed $value, string $key, array $datas, array &$errors, bool $required = true, string $message = 'Cette valeur existe déjà'): bool
    {
        if (!$required && empty($value)) {
            return true;
        }
        foreach ($datas as $data) {
            if (is_array($data) && isset($data[$key]) && $data[$key] == $value) {
                $errors[$key] = $message;
                return false;
            }
            if (is_object($data) && isset($data->$key) && $data->$key == $value) {
                $errors[$key] = $message;
                return false;
            }
        }
        return true;
    }

   
    public static function isEmail(mixed $value, string $key, array &$errors, string $message = 'Adresse email invalide'): bool
    {
        if (!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $errors[$key] = $message;
            return false;
        }
        return true;
    }

    public static function numeric(mixed $value): bool
    {
        return is_numeric($value);
    }


    public static function isPositive(mixed $value, string $key, array &$errors, string $message = 'Ce champ doit contenir un nombre positif'): bool
    {
        if (!is_numeric($value) || (float) $value < 0) {
            $errors[$key] = $message;
            return false;
        }
        return true;
    }
}