<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    //

    public function index(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }

        $filters = $request->validate([
            'status' => 'nullable|string|in:pending,completed,in_progress',
            'created_at' => 'nullable|date',
        ]);

        $tasks = Task::query()
            ->where('user_id', $user->id)
            ->when($request->filled('status'), function ($query) use ($filters) {
                $query->where('status', $filters['status']);
            })
            ->when($request->filled('created_at'), function ($query) use ($filters) {
                $query->whereDate('created_at', $filters['created_at']);
            })
            ->latest()
            ->get();

            return response()->json([
                'message' => 'List of Tasks successfully',
                'data' => $tasks->map(function ($task) {
                    return [
                        'title' => $task->title,
                        'description' => $task->description,
                        'status' => $task->status,
                    ];
                }),
            ], 200);
    }

    public function store(StoreTaskRequest $request)
    {
        $validated = $request->validated();
        $validated['user_id'] = $request->user()->id;

        $task = Task::create($validated);

        return response()->json([
            'message' => 'Task created successfully',
            'data' => $task,
        ], 201);
    }

    public function show(Request $request, $id)
    {
        $task = Task::where('user_id', $request->user()->id)->find($id);

        if (!$task) {
            return response()->json([
                'message' => 'Task not found',
            ], 404);
        }

        return response()->json([
            'message' => 'Task fetched successfully',
            'data' => $task,
        ], 200);
    }

    public function update(UpdateTaskRequest $request, $id)
    {
        $validated = $request->validated();

        $task = Task::where('user_id', $request->user()->id)->find($id);

        if (!$task) {
            return response()->json([
                'message' => 'Task not found',
            ], 404);
        }

        $task->update($validated);

        return response()->json([
            'message' => 'Task updated successfully',
            'data' => $task,
        ], 200);
    }

    public function destroy(Request $request, $id)
    {
        $task = Task::where('user_id', $request->user()->id)->find($id);

        if (!$task) {
            return response()->json([
                'message' => 'Task not found',
            ], 404);
        }

        $task->delete();

        return response()->json([
            'message' => 'Task deleted successfully',
        ], 200);
    }


}
