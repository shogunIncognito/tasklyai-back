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
        $tasks = Task::where('user_id', $request->user()->id)->get();
        return response()->json($tasks);
    }

    public function createTask(Request $request)
    {
        $rules = TaskValidator::createRules();

        // get json task with ai prompt
        $task = AIService::promptTaskToJson($request->prompt);

        $validator = Validator::make($task, $rules);

        if ($validator->fails()) {
            return response()->json([
                "message" => "error validating data prompt malformed",
                "errors" => $validator->errors(),
                "task" => $task
            ], 400);
        }

        $newTask = Task::create([
            "title" => $task['title'],
            "date" => $task['date'],
            "category" => $task['category'],
            "priority" => $task['priority'],
            "user_id" => $request->user()->id
        ]);

        $newTask->save();

        return response()->json($newTask);
    }

    public function updateTask(Request $request, $id)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json([
                "message" => "task not found"
            ], 404);
        }

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
        $task = Task::find($id);

        if (!$task) {
            return response()->json([
                "message" => "task not found"
            ], 404);
        }

        $task->delete();

        return response()->json([
            "message" => "task deleted"
        ]);
    }
}
