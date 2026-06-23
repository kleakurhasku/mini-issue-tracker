@extends('layouts.app')

@section('content')
<div class="flex justify-between items-center mb-4">
    <h1 class="text-2xl font-bold">Projects</h1>
    <a href="{{ route('projects.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded">+ New Project</a>
</div>

<div class="bg-white rounded shadow divide-y">
    @forelse ($projects as $project)
        <div class="p-4 flex justify-between items-center">
            <div>
                <a href="{{ route('projects.show', $project) }}" class="font-semibold text-indigo-600">
                    {{ $project->name }}
                </a>
                <p class="text-sm text-gray-500">{{ $project->issues_count }} issue</p>
            </div>
            <div class="flex gap-2">
                @can('update', $project)
                    <a href="{{ route('projects.edit', $project) }}" class="text-blue-600">Edit</a>
                @endcan
                @can('delete', $project)
                    <form action="{{ route('projects.destroy', $project) }}" method="POST"
                          onsubmit="return confirm('Are you sure?')">
                        @csrf
                        @method('DELETE')
                        <button class="text-red-600">Delete</button>
                    </form>
                @endcan
            </div>
        </div>
    @empty
        <p class="p-4 text-gray-500">No projects yet.</p>
    @endforelse
</div>
@endsection