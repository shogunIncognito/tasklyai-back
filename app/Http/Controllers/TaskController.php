<?php

namespace App\Http\Controllers;

use App\Http\Services\AIService;
use App\Models\Task;
use App\Validators\TaskValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    public function getUserTasks(Request $request)
    {
        $tasks = Task::where('id', $request->user()->id)->get();
        return response()->json($tasks);
    }

    public function createTask(Request $request)
    {
        $rules = TaskValidator::createRules();

        // get json with ai prompt
        $task = AIService::promptTaskToJson($request->all());
        // get json with ai prompt

        $validator = Validator::make([], $rules);

        if ($validator->fails()) {
            return response()->json([
                "message" => "error validating data",
                "errors" => $validator->errors()
            ], 400);
        }

        $newTask = Task::create([
            "title" => $task['title'],
            "date" => $task['date'],
            "category" => $task['category'],
            "priority" => $task['priority']
        ]);

        $newTask->save();

        return response()->json($newTask);
    }

    public function updateTask(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        $rules = TaskValidator::updateRules();
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                "message" => "error validating data",
                "errors" => $validator->errors()
            ], 400);
        }

        $task->update($validator->getData());

        return response()->json($task);
    }

    public function deleteTask($id)
    {
        $task = Task::findOrFail($id);

        $task->delete();

        return response()->json([
            "task deleted"
        ]);
    }
}
