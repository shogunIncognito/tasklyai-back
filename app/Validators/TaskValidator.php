<?php

namespace App\Validators;

use Illuminate\Validation\Rule;

class TaskValidator
{

    private static $validCategories = ['Personal', 'Trabajo', 'Estudio', 'Compras', 'Hogar', 'Salud', 'Social', 'Tecnología', 'Entretenimiento', 'Proyectos'];
    private static $validPriorities = ['Alta', 'Media', 'Baja', 'Urgente', 'Normal', 'Opcional'];


    public static function createRules()
    {
        return [
            "title" => "required",
            "date" => "required",
            "priority" => ["required", Rule::in(self::$validPriorities)],
            "category" => ["required", Rule::in(self::$validCategories)]
        ];
    }

    public static function updateRules()
    {
        return [
            "title" => "nullable",
            "date" => "nullable",
            "priority" => ["nullable", Rule::in(self::$validPriorities)],
            "category" => ["nullable", Rule::in(self::$validCategories)]
        ];
    }

    public static function getCategories()
    {
        return self::$validCategories;
    }

    public static function getPriorities()
    {
        return self::$validPriorities;
    }
}
