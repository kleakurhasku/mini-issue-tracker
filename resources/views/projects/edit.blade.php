@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-4">Edit Project</h1>

<form action="{{ route('projects.update', $project) }}" method="POST" class="bg-white p-6 rounded shadow space-y-4">
    @csrf
    @method('PUT')
    @include('projects.form')
    <button class="bg-indigo-600 text-white px-4 py-2 rounded">Përditëso</button>
</form>
@endsection