@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-4">Projekt i ri</h1>

<form action="{{ route('projects.store') }}" method="POST" class="bg-white p-6 rounded shadow space-y-4">
    @csrf
    @include('projects.form')
    <button class="bg-indigo-600 text-white px-4 py-2 rounded">Ruaj</button>
</form>
@endsection