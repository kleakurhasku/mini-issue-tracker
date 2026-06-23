@extends('layouts.app')

@section('content')
<div class="flex justify-between items-center mb-4">
    <h1 class="text-2xl font-bold">Issues</h1>
    <a href="{{ route('issues.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded">+ Issue i ri</a>
</div>

{{-- Filtrat --}}
<form method="GET" action="{{ route('issues.index') }}" class="bg-white p-4 rounded shadow mb-4 flex gap-4 items-end">
    <div>
        <label class="block text-sm font-medium">Status</label>
        <select name="status" class="border rounded px-3 py-2">
            <option value="">Të gjitha</option>
            @foreach (['open', 'in_progress', 'closed'] as $s)
                <option value="{{ $s }}" @selected(request('status') === $s)>{{ $s }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium">Priority</label>
        <select name="priority" class="border rounded px-3 py-2">
            <option value="">Të gjitha</option>
            @foreach (['low', 'medium', 'high'] as $p)
                <option value="{{ $p }}" @selected(request('priority') === $p)>{{ $p }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium">Tag</label>
        <select name="tag" class="border rounded px-3 py-2">
            <option value="">Të gjithë</option>
            @foreach ($tags as $tag)
                <option value="{{ $tag->id }}" @selected((string) request('tag') === (string) $tag->id)>{{ $tag->name }}</option>
            @endforeach
        </select>
    </div>
    <button class="bg-gray-700 text-white px-4 py-2 rounded">Filtro</button>
    <a href="{{ route('issues.index') }}" class="text-gray-500 px-2 py-2">Pastro</a>
</form>

{{-- Lista --}}
<div class="bg-white rounded shadow divide-y">
    @forelse ($issues as $issue)
        <div class="p-4 flex justify-between items-center">
            <div>
                <a href="{{ route('issues.show', $issue) }}" class="font-semibold text-indigo-600">
                    {{ $issue->title }}
                </a>
                <p class="text-sm text-gray-500">{{ $issue->project->name }}</p>
                <div class="flex gap-1 mt-1">
                    @foreach ($issue->tags as $tag)
                        <span class="text-xs px-2 py-0.5 rounded text-white"
                              style="background-color: {{ $tag->color ?? '#6b7280' }}">{{ $tag->name }}</span>
                    @endforeach
                </div>
            </div>
            <div class="flex gap-3 items-center text-xs">
                <span class="px-2 py-1 rounded bg-gray-200">{{ $issue->status }}</span>
                <span class="px-2 py-1 rounded bg-gray-200">{{ $issue->priority }}</span>
                <a href="{{ route('issues.edit', $issue) }}" class="text-blue-600">Edito</a>
                <form action="{{ route('issues.destroy', $issue) }}" method="POST"
                      onsubmit="return confirm('A je i sigurt?')">
                    @csrf
                    @method('DELETE')
                    <button class="text-red-600">Fshi</button>
                </form>
            </div>
        </div>
    @empty
        <p class="p-4 text-gray-500">S'ka issue që përputhen.</p>
    @endforelse
</div>
@endsection