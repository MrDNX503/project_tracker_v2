<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;

class ProjectController extends Controller
{
    /**
     * Muestra la lista de proyectos.
     */
    public function index(): \Inertia\Response
    {
        // Enviar todos los datos de los proyectos, incluyendo las fechas.
        $projects = Project::all();
        return Inertia::render('Projects/Index', [
            'projects' => $projects,
        ]);
    }

    /**
     * Muestra el formulario para crear un nuevo proyecto.
     */
    public function create(): \Inertia\Response
    {
        return Inertia::render('Projects/Create');
    }

    /**
     * Guarda un nuevo proyecto en la base de datos.
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'progress' => 'required|integer|min:0|max:100',
                'priority' => 'required|integer|min:0|max:2',
            ]);

            Project::create([
                'title' => $validatedData['title'],
                'description' => $validatedData['description'],
                'start_date' => $validatedData['start_date'],
                'end_date' => $validatedData['end_date'],
                'progress' => $validatedData['progress'],
                'priority' => $validatedData['priority'],
                'user_id' => Auth::id(),
            ]);

            return redirect()->route('projects.index')->with('success', '¡Proyecto creado con éxito!');
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }
    }

    /**
     * Muestra el formulario para editar un proyecto.
     */
    public function edit(Project $project): \Inertia\Response
    {
        // El formulario de edición ya recibe el objeto Project completo
        return Inertia::render('Projects/Edit', [
            'project' => $project,
        ]);
    }

    /**
     * Actualiza un proyecto en la base de datos.
     */
    public function update(Request $request, Project $project): RedirectResponse
    {
        try {
            // Lógica para actualizar solo el progreso desde el índice.
            if ($request->has('progress') && count($request->all()) === 1) {
                $validatedData = $request->validate(['progress' => 'required|integer|min:0|max:100']);
            } else {
                // Validación para la actualización completa desde el formulario de edición.
                // Aquí usamos 'sometimes' para que solo se valide si el campo está presente.
                $validatedData = $request->validate([
                    'title' => 'sometimes|required|string|max:255',
                    'description' => 'nullable|string',
                    'start_date' => 'sometimes|required|date',
                    'end_date' => 'sometimes|required|date|after_or_equal:start_date',
                    'priority' => 'sometimes|required|integer|min:0|max:2',
                    // No validamos 'progress' aquí para que no cause error si no se envía.
                ]);
            }

            // Usamos 'fill' para actualizar solo los campos validados.
            $project->fill($validatedData);
            $project->save();

            return redirect()->route('projects.index')->with('success', '¡Proyecto actualizado con éxito!');
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }
    }

    /**
     * Elimina un proyecto de la base de datos.
     */
    public function destroy(Project $project): RedirectResponse
    {
        $project->delete();
        return redirect()->route('projects.index')->with('success', '¡Proyecto eliminado con éxito!');
    }

    /**
     * Muestra el gráfico de progreso de los proyectos.
     */
    public function progress(): \Inertia\Response
    {
        $projects = Project::all();
        return Inertia::render('Projects/ProjectProgress', [
            'projects' => $projects,
        ]);
    }
}
