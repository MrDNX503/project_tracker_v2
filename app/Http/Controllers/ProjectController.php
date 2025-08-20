<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Task;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        return Project::with('tasks')->get();
    }

    public function store(Request $request)
    {
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'start_date' => 'nullable|date',
        'end_date' => 'nullable|date|after_or_equal:start_date',
    ]);

    // 🔑 Revisar si hay usuario autenticado
    if ($request->user()) {
        $validated['user_id'] = $request->user()->id;
    } else {
        // modo invitado (puedes dejar null o asignar un "user_id" genérico, ej. 1)
        $validated['user_id'] = null;
    }

    $project = Project::create($validated);

    return response()->json($project, 201);
    }


    public function taskIndex(Request $request)
    {
        return Task::with('project')->get();
    }

    public function taskStore(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'in:low,medium,high',
            'notes' => 'nullable|string',
            'due_date' => 'nullable|date',
            'status' => 'in:pending,in_progress,completed',
            'project_id' => 'required|exists:projects,id',
        ]);

        $task = Task::create($validated);
        return response()->json($task, 201);
    }
}