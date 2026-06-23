@extends('layouts.app')

@section('content')
<div class="flex justify-between items-center mb-4">
    <h1 class="text-2xl font-bold">Projektet</h1>
    <a href="{{ route('projects.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded">+ Projekt i ri</a>
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
                <a href="{{ route('projects.edit', $project) }}" class="text-blue-600">Edito</a>
                <form action="{{ route('projects.destroy', $project) }}" method="POST"
                      onsubmit="return confirm('A je i sigurt?')">
                    @csrf
                    @method('DELETE')
                    <button class="text-red-600">Fshi</button>
                </form>
            </div>
        </div>
    @empty
        <p class="p-4 text-gray-500">Ende s'ka projekte.</p>
    @endforelse
</div>
@endsection
