@extends('layouts.app')

@section('content')
<div class="flex justify-between items-center mb-4">
    <h1 class="text-2xl font-bold">{{ $project->name }}</h1>
    <a href="{{ route('projects.index') }}" class="text-indigo-600">← Back to projects</a>
</div>

<div class="bg-white rounded shadow p-6 mb-6">
    <p class="text-gray-700 mb-3">{{ $project->description ?? 'No description provided.' }}</p>
    <div class="flex gap-6 text-sm text-gray-500">
        <span>Start date: {{ $project->start_date?->format('d/m/Y') ?? '—' }}</span>
        <span>Due date: {{ $project->deadline?->format('d/m/Y') ?? '—' }}</span>
    </div>
</div>

<h2 class="text-xl font-semibold mb-3">Issues ({{ $project->issues->count() }})</h2>

<div class="bg-white rounded shadow divide-y">
    @forelse ($project->issues as $issue)
        <div class="p-4">
            <div class="flex justify-between items-center">
                <span class="font-medium">{{ $issue->title }}</span>
                <div class="flex gap-2 text-xs">
                    <span class="px-2 py-1 rounded bg-gray-200">{{ $issue->status }}</span>
                    <span class="px-2 py-1 rounded bg-gray-200">{{ $issue->priority }}</span>
                </div>
            </div>
            @if ($issue->tags->count())
                <div class="flex gap-1 mt-2">
                    @foreach ($issue->tags as $tag)
                        <span class="text-xs px-2 py-0.5 rounded text-white"
                              style="background-color: {{ $tag->color ?? '#6b7280' }}">
                            {{ $tag->name }}
                        </span>
                    @endforeach
                </div>
            @endif
        </div>
    @empty
        <p class="p-4 text-gray-500">This project has no issues yet.</p>
    @endforelse
</div>
@endsection