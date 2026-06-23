@php $issue = $issue ?? null; @endphp

<div>
    <label class="block text-sm font-medium">Project</label>
    <select name="project_id" class="border rounded w-full px-3 py-2">
        @foreach ($projects as $project)
            <option value="{{ $project->id }}" @selected(old('project_id', $issue->project_id ?? '') == $project->id)>
                {{ $project->name }}
            </option>
        @endforeach
    </select>
    @error('project_id') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
</div>

<div>
    <label class="block text-sm font-medium">Title</label>
    <input type="text" name="title" value="{{ old('title', $issue->title ?? '') }}"
           class="border rounded w-full px-3 py-2">
    @error('title') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
</div>

<div>
    <label class="block text-sm font-medium">Description</label>
    <textarea name="description" class="border rounded w-full px-3 py-2">{{ old('description', $issue->description ?? '') }}</textarea>
    @error('description') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
</div>

<div class="flex gap-4">
    <div class="flex-1">
        <label class="block text-sm font-medium">Status</label>
        <select name="status" class="border rounded w-full px-3 py-2">
            @foreach (['open', 'in_progress', 'closed'] as $s)
                <option value="{{ $s }}" @selected(old('status', $issue->status ?? 'open') === $s)>{{ $s }}</option>
            @endforeach
        </select>
    </div>
    <div class="flex-1">
        <label class="block text-sm font-medium">Priority</label>
        <select name="priority" class="border rounded w-full px-3 py-2">
            @foreach (['low', 'medium', 'high'] as $p)
                <option value="{{ $p }}" @selected(old('priority', $issue->priority ?? 'medium') === $p)>{{ $p }}</option>
            @endforeach
        </select>
    </div>
    <div class="flex-1">
        <label class="block text-sm font-medium">Due date</label>
        <input type="date" name="due_date"
               value="{{ old('due_date', isset($issue->due_date) ? $issue->due_date->format('Y-m-d') : '') }}"
               class="border rounded w-full px-3 py-2">
        @error('due_date') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>
</div>