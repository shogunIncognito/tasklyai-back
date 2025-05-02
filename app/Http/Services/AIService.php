<?php

namespace App\Http\Services;

use App\Validators\TaskValidator;
use OpenAI\Laravel\Facades\OpenAI;

class AIService
{
    public static function promptTaskToJson(String $prompt)
    {
        $today = now()->toFormattedDateString();
        $categories = TaskValidator::getCategories();
        $priorities = TaskValidator::getPriorities();

        $response = OpenAI::chat()->create([
            "model" => "gpt-3.5-turbo",
            "messages" => [
                [
                    "role" => "system",
                    "content" => "Hoy es " . $today . " Convierte el siguiente texto en un JSON con los campos: title, date, priority y category.
                    evita colocar saltos de linea, si es necesario hacerlo en una sola linea
                    Categorías válidas: " . implode(', ', $categories) .
                        " Prioridades válidas: " . implode(', ', $priorities) .
                        " Al principio del prompt se indica que fecha es hoy la idea es que la pongas en fecha ISOString, es decir algo como esto '2025-05-02', entonces el 'date' colócalo en base a eso
                    ej: date: 'Mayo 1, 2025' entonces '2025-05-01' si en el texto enviado se indica 'mañana' colocas '2025-05-02' haciéndolo en base al dia actual
                    si no se indica fecha entonces colocar la fecha de hoy.
                    Reformula el texto como un título breve, claro y profesional que resuma la tarea. Evita repetir el texto original tal cual y usa un lenguaje directo.                         
                    Escoge la categoría y la prioridad mas adecuada a la tarea, si ninguna lo es, deja 'Personal' por defecto para categoria y en prioridad deja 'Normal'
                    Texto: " . $prompt .
                        " Respuesta: "
                ]
            ]
        ]);

        return json_decode($response->choices[0]->message->content, true);
    }
}
