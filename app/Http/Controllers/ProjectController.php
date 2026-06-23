<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use App\Models\Project;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::withCount('issues')->latest()->get();
        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store(ProjectRequest $request)
    {
        // Projekti i ri merr si pronar përdoruesin e kyçur
        $data = $request->validated();
        $data['user_id'] = auth()->id();

        Project::create($data);
        return redirect()->route('projects.index')->with('success', 'Projekti u krijua.');
    }

    public function show(Project $project)
    {
        $project->load('issues.tags');
        return view('projects.show', compact('project'));
    }

    public function edit(Project $project)
    {
        $this->authorize('update', $project); // vetëm pronari
        return view('projects.edit', compact('project'));
    }

    public function update(ProjectRequest $request, Project $project)
    {
        $this->authorize('update', $project); // vetëm pronari
        $project->update($request->validated());
        return redirect()->route('projects.index')->with('success', 'Projekti u përditësua.');
    }

    public function destroy(Project $project)
    {
        $this->authorize('delete', $project); // vetëm pronari
        $project->delete();
        return redirect()->route('projects.index')->with('success', 'Projekti u fshi.');
    }
}