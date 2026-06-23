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
        Project::create($request->validated());
        return redirect()->route('projects.index')->with('success', 'Projekti u krijua.');
    }

    
    public function show(Project $project)
    {
        $project->load('issues.tags');
        return view('projects.show', compact('project'));
    }

   
    public function edit(Project $project)
    {
        return view('projects.edit', compact('project'));
    }

   
    public function update(ProjectRequest $request, Project $project)
    {
        $project->update($request->validated());
        return redirect()->route('projects.index')->with('success', 'Projekti u përditësua.');
    }

   
    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->route('projects.index')->with('success', 'Projekti u fshi.');
    }
}