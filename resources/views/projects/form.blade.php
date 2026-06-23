@php $project = $project ?? null; @endphp

<div>
    <label class="block text-sm font-medium">Emri</label>
    <input type="text" name="name" value="{{ old('name', $project->name ?? '') }}"
           class="border rounded w-full px-3 py-2">
    @error('name') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
</div>

<div>
    <label class="block text-sm font-medium">Përshkrimi</label>
    <textarea name="description" class="border rounded w-full px-3 py-2">{{ old('description', $project->description ?? '') }}</textarea>
    @error('description') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
</div>

<div class="flex gap-4">
    <div class="flex-1">
        <label class="block text-sm font-medium">Data e fillimit</label>
        <input type="date" name="start_date"
               value="{{ old('start_date', isset($project->start_date) ? $project->start_date->format('Y-m-d') : '') }}"
               class="border rounded w-full px-3 py-2">
    </div>
    <div class="flex-1">
        <label class="block text-sm font-medium">Afati (deadline)</label>
        <input type="date" name="deadline"
               value="{{ old('deadline', isset($project->deadline) ? $project->deadline->format('Y-m-d') : '') }}"
               class="border rounded w-full px-3 py-2">
        @error('deadline') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>
</div>