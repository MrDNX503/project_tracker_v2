<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function store(Request $request)
    {
        // Validar los datos del formulario
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        // Crear el proyecto
        $project = Project::create($validatedData);

        return response()->json($project, 201);
    }

    public function index()
    {
        $projects = Project::all(); // Puedes añadir lógica de filtrado aquí si lo necesitas
        return response()->json($projects);
    }
}