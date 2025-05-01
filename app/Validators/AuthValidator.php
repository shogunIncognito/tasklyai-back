<?php

namespace App\Validators;

class AuthValidator
{
    public static function registerRules()
    {
        return [
            "firstname" => "required|max:255",
            "lastname" => "required|max:255",
            "email" => "required|unique:users",
            "password" => "required|max:255",
        ];
    }
}
