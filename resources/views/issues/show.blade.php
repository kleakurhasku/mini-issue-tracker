@extends('layouts.app')

@section('content')
<div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between mb-6">
    <div>
        <span class="text-xs uppercase tracking-[0.24em] text-sky-600">Issue Details</span>
        <h1 class="mt-2 text-3xl font-semibold text-slate-900">{{ $issue->title }}</h1>
    </div>
    <a href="{{ route('issues.index') }}" class="text-sky-700 hover:text-sky-900 font-medium">← Back to issues</a>
</div>

{{-- Details --}}
<div class="card p-6 mb-6">
    <p class="text-slate-700 leading-7 mb-5">{{ $issue->description ?? 'No description provided.' }}</p>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 text-sm text-slate-500">
        <div class="space-y-1">
            <span class="block text-slate-400">Project</span>
            <p class="font-medium text-slate-900">{{ $issue->project->name }}</p>
        </div>
        <div class="space-y-1">
            <span class="block text-slate-400">Status</span>
            <p class="font-medium text-slate-900">{{ $issue->status }}</p>
        </div>
        <div class="space-y-1">
            <span class="block text-slate-400">Priority</span>
            <p class="font-medium text-slate-900">{{ $issue->priority }}</p>
        </div>
        <div class="space-y-1">
            <span class="block text-slate-400">Due date</span>
            <p class="font-medium text-slate-900">{{ $issue->due_date?->format('d/m/Y') ?? '—' }}</p>
        </div>
    </div>
</div>

{{-- TAGS (me AJAX) --}}
<div class="card p-6 mb-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
        <h2 class="text-xl font-semibold text-slate-900">Tags</h2>
        <p class="text-sm text-slate-500">Manage the tags associated with this issue.</p>
    </div>

    <div id="tags-list" class="flex flex-wrap gap-2 mb-4">
        @forelse ($issue->tags as $tag)
            <span class="text-xs px-3 py-1 rounded-full text-white flex items-center gap-2"
                  style="background-color: {{ $tag->color ?? '#4f46e5' }}">
                {{ $tag->name }}
                <button data-id="{{ $tag->id }}" class="detach-btn font-bold hover:text-slate-200">×</button>
            </span>
        @empty
            <span class="text-gray-400 text-sm">No tags.</span>
        @endforelse
    </div>

    <div class="flex flex-col sm:flex-row sm:items-center gap-3">
        <select id="tag-select" class="border border-slate-300 rounded-xl px-4 h-10 bg-white text-slate-700 w-full sm:w-auto min-w-[160px]">
            <option value="">Select a tag...</option>
            @foreach ($allTags as $tag)
                <option value="{{ $tag->id }}">{{ $tag->name }}</option>
            @endforeach
        </select>
        <button id="add-tag-btn" class="inline-flex items-center justify-center rounded-full bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 font-semibold shadow transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41L11 3 3 11l8.59 8.59a2 2 0 002.82 0L20.59 16.23a2 2 0 000-2.82z"/><circle cx="7.5" cy="7.5" r="1.5"/></svg>
            Add tag
        </button>
    </div>
</div>

<div class="card p-6 mb-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
        <h2 class="text-xl font-semibold text-slate-900">Issue members</h2>
        <p class="text-sm text-slate-500">Add or remove team members.</p>
    </div>

    <div id="members-list" class="flex flex-wrap gap-2 mb-4"></div>

    <div class="flex flex-col sm:flex-row sm:items-center gap-3">
        <select id="member-select" class="border border-slate-300 rounded-xl px-4 h-10 bg-white text-slate-700 w-full sm:w-auto min-w-[160px]">
            <option value="">Select a member...</option>
            @foreach ($allUsers as $user)
                <option value="{{ $user->id }}">{{ $user->name }}</option>
            @endforeach
        </select>
        <button id="add-member-btn" class="inline-flex items-center justify-center rounded-full bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 font-semibold shadow transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 11c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3z"/><path d="M2 20c0-2.21 3.58-4 8-4s8 1.79 8 4"/></svg>
            Add member
        </button>
    </div>
</div>

{{-- COMMENTS (with AJAX) --}}
<div class="card p-6">
    <h2 class="text-xl font-semibold mb-4 text-slate-900">Comments</h2>

    {{-- New comment form --}}
    <div class="mb-6 space-y-3">
        <input type="text" id="comment-author" placeholder="Your name"
               class="border border-slate-300 rounded-2xl w-full px-4 py-3 focus:border-sky-400 focus:ring-2 focus:ring-sky-200 outline-none transition" />
        <p id="author-error" class="text-red-600 text-sm hidden"></p>

        <textarea id="comment-body" placeholder="Write a comment..."
                  class="border border-slate-300 rounded-2xl w-full px-4 py-3 min-h-[120px] focus:border-sky-400 focus:ring-2 focus:ring-sky-200 outline-none transition"></textarea>
        <p id="body-error" class="text-red-600 text-sm hidden"></p>

        <button id="add-comment-btn" class="inline-flex items-center justify-center rounded-full bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 font-semibold shadow transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>
            Add comment
        </button>
    </div>

    {{-- Lista e komenteve --}}
    <div id="comments-list" class="space-y-4"></div>
    <button id="load-more-btn" class="mt-5 text-sky-600 font-medium hidden">Load more</button>
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
            tagsList.innerHTML = '<span class="text-gray-400 text-sm">No tags.</span>';
            return;
        }
        tags.forEach(tag => {
            const span = document.createElement('span');
            span.className = 'text-xs px-3 py-1 rounded-full text-white flex items-center gap-2';
            span.style.backgroundColor = tag.color || '#4f46e5';
            span.innerHTML = `${tag.name} <button data-id="${tag.id}" class="detach-btn font-bold hover:text-slate-200">×</button>`;
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

    // ---------- MEMBERS ----------
    const membersList = document.getElementById('members-list');

    function renderMembers(members) {
        membersList.innerHTML = '';
        if (members.length === 0) {
            membersList.innerHTML = '<span class="text-gray-400 text-sm">No members.</span>';
            return;
        }
        members.forEach(member => {
            const span = document.createElement('span');
            span.className = 'text-xs px-3 py-1 rounded-full bg-slate-900 text-white flex items-center gap-2';
            span.innerHTML = `${member.name} <button data-id="${member.id}" class="detach-member-btn font-bold hover:text-slate-200">×</button>`;
            membersList.appendChild(span);
        });
    }

    document.getElementById('add-member-btn').addEventListener('click', () => {
        const userId = document.getElementById('member-select').value;
        if (!userId) return;

        fetch(`/issues/${issueId}/members`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({ user_id: userId })
        })
        .then(res => res.json())
        .then(members => renderMembers(members));
    });

    membersList.addEventListener('click', (e) => {
        if (e.target.classList.contains('detach-member-btn')) {
            const userId = e.target.dataset.id;
            fetch(`/issues/${issueId}/members/${userId}`, {
                method: 'DELETE',
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }
            })
            .then(res => res.json())
            .then(members => renderMembers(members));
        }
    });

    renderMembers(@json($issue->members));

    // ---------- KOMENTET ----------
    const commentsList = document.getElementById('comments-list');
    const loadMoreBtn = document.getElementById('load-more-btn');
    let nextPageUrl = `/issues/${issueId}/comments`;

    function commentElement(c) {
        const div = document.createElement('div');
        div.className = 'border border-slate-200 rounded-3xl bg-slate-50 p-4 shadow-sm';
        div.innerHTML = `<p class="font-semibold text-slate-900 mb-2">${c.author_name}</p><p class="text-slate-700 leading-7">${c.body}</p>`;
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