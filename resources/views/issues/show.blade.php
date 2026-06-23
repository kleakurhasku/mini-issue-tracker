@extends('layouts.app')

@section('content')
<div class="flex justify-between items-center mb-4">
    <h1 class="text-2xl font-bold">{{ $issue->title }}</h1>
    <a href="{{ route('issues.index') }}" class="text-indigo-600">← Kthehu te issues</a>
</div>

{{-- Detajet --}}
<div class="bg-white rounded shadow p-6 mb-6">
    <p class="text-gray-700 mb-3">{{ $issue->description ?? 'Pa përshkrim.' }}</p>
    <div class="flex gap-6 text-sm text-gray-500">
        <span>Projekti: {{ $issue->project->name }}</span>
        <span>Status: {{ $issue->status }}</span>
        <span>Priority: {{ $issue->priority }}</span>
        <span>Afati: {{ $issue->due_date?->format('d/m/Y') ?? '—' }}</span>
    </div>
</div>

{{-- TAGS (me AJAX) --}}
<div class="bg-white rounded shadow p-6 mb-6">
    <h2 class="text-lg font-semibold mb-3">Tags</h2>

    <div id="tags-list" class="flex flex-wrap gap-2 mb-4"></div>

    <div class="flex gap-2">
        <select id="tag-select" class="border rounded px-3 py-2">
            <option value="">Zgjidh një tag...</option>
            @foreach ($allTags as $tag)
                <option value="{{ $tag->id }}">{{ $tag->name }}</option>
            @endforeach
        </select>
        <button id="add-tag-btn" class="bg-indigo-600 text-white px-4 py-2 rounded">Shto tag</button>
    </div>
</div>

{{-- KOMENTET (me AJAX) --}}
<div class="bg-white rounded shadow p-6">
    <h2 class="text-lg font-semibold mb-3">Komentet</h2>

    {{-- Forma për koment të ri --}}
    <div class="mb-4 space-y-2">
        <input type="text" id="comment-author" placeholder="Emri juaj"
               class="border rounded w-full px-3 py-2">
        <p id="author-error" class="text-red-600 text-sm hidden"></p>

        <textarea id="comment-body" placeholder="Shkruaj një koment..."
                  class="border rounded w-full px-3 py-2"></textarea>
        <p id="body-error" class="text-red-600 text-sm hidden"></p>

        <button id="add-comment-btn" class="bg-indigo-600 text-white px-4 py-2 rounded">Shto koment</button>
    </div>

    {{-- Lista e komenteve --}}
    <div id="comments-list" class="space-y-3"></div>
    <button id="load-more-btn" class="mt-4 text-indigo-600 hidden">Ngarko më shumë</button>
</div>

{{-- JavaScript-i për AJAX --}}
<script>
    const issueId = {{ $issue->id }};
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    // ---------- TAGS ----------
    const tagsList = document.getElementById('tags-list');

    function renderTags(tags) {
        tagsList.innerHTML = '';
        if (tags.length === 0) {
            tagsList.innerHTML = '<span class="text-gray-400 text-sm">Pa tags.</span>';
            return;
        }
        tags.forEach(tag => {
            const span = document.createElement('span');
            span.className = 'text-xs px-2 py-1 rounded text-white flex items-center gap-1';
            span.style.backgroundColor = tag.color || '#6b7280';
            span.innerHTML = `${tag.name} <button data-id="${tag.id}" class="detach-btn font-bold">×</button>`;
            tagsList.appendChild(span);
        });
    }

    document.getElementById('add-tag-btn').addEventListener('click', () => {
        const tagId = document.getElementById('tag-select').value;
        if (!tagId) return;

        fetch(`/issues/${issueId}/tags`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({ tag_id: tagId })
        })
        .then(res => res.json())
        .then(tags => renderTags(tags));
    });

    tagsList.addEventListener('click', (e) => {
        if (e.target.classList.contains('detach-btn')) {
            const tagId = e.target.dataset.id;
            fetch(`/issues/${issueId}/tags/${tagId}`, {
                method: 'DELETE',
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }
            })
            .then(res => res.json())
            .then(tags => renderTags(tags));
        }
    });

    renderTags(@json($issue->tags));

    // ---------- KOMENTET ----------
    const commentsList = document.getElementById('comments-list');
    const loadMoreBtn = document.getElementById('load-more-btn');
    let nextPageUrl = `/issues/${issueId}/comments`;

    function commentElement(c) {
        const div = document.createElement('div');
        div.className = 'border rounded p-3';
        div.innerHTML = `<p class="font-medium">${c.author_name}</p><p class="text-gray-700">${c.body}</p>`;
        return div;
    }

    function loadComments() {
        if (!nextPageUrl) return;
        fetch(nextPageUrl, {
            headers: { 'Accept': 'application/json' }
        })
            .then(res => res.json())
            .then(data => {
                data.data.forEach(c => commentsList.appendChild(commentElement(c)));
                nextPageUrl = data.next_page_url;
                loadMoreBtn.classList.toggle('hidden', !nextPageUrl);
            });
    }

    loadMoreBtn.addEventListener('click', loadComments);
    loadComments();

    document.getElementById('add-comment-btn').addEventListener('click', () => {
        const author = document.getElementById('comment-author');
        const body = document.getElementById('comment-body');
        const authorError = document.getElementById('author-error');
        const bodyError = document.getElementById('body-error');

        authorError.classList.add('hidden');
        bodyError.classList.add('hidden');

        fetch(`/issues/${issueId}/comments`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({ author_name: author.value, body: body.value })
        })
        .then(res => {
            if (res.status === 422) {
                return res.json().then(data => { throw data.errors; });
            }
            return res.json();
        })
        .then(comment => {
            commentsList.prepend(commentElement(comment));
            author.value = '';
            body.value = '';
        })
        .catch(errors => {
            if (errors.author_name) {
                authorError.textContent = errors.author_name[0];
                authorError.classList.remove('hidden');
            }
            if (errors.body) {
                bodyError.textContent = errors.body[0];
                bodyError.classList.remove('hidden');
            }
        });
    });
</script>
@endsection