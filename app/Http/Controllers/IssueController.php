<?php

namespace App\Http\Controllers;

use App\Http\Requests\IssueRequest;
use App\Models\Issue;
use App\Models\Project;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class IssueController extends Controller
{
    public function index(Request $request)
    {
        $query = Issue::with(['project', 'tags']); // eager loading – pa N+1

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('tag')) {
            $query->whereHas('tags', fn ($q) => $q->where('tags.id', $request->tag));
        }

        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $issues = $query->latest()->get();
        $tags = Tag::orderBy('name')->get();

       
        if ($request->ajax()) {
            return response()->json($issues);
        }

        return view('issues.index', compact('issues', 'tags'));
    }

    public function create()
    {
        $projects = Project::orderBy('name')->get();
        return view('issues.create', compact('projects'));
    }

    public function store(IssueRequest $request)
    {
        Issue::create($request->validated());
        return redirect()->route('issues.index')->with('success', 'Issue u krijua.');
    }

    public function show(Issue $issue)
    {
        $issue->load(['project', 'tags', 'comments', 'members']);
        $allTags = Tag::orderBy('name')->get();
        $allUsers = User::orderBy('name')->get();
        return view('issues.show', compact('issue', 'allTags', 'allUsers'));
    }

    public function edit(Issue $issue)
    {
        $projects = Project::orderBy('name')->get();
        return view('issues.edit', compact('issue', 'projects'));
    }

    public function update(IssueRequest $request, Issue $issue)
    {
        $issue->update($request->validated());
        return redirect()->route('issues.index')->with('success', 'Issue u përditësua.');
    }

    public function destroy(Issue $issue)
    {
        $issue->delete();
        return redirect()->route('issues.index')->with('success', 'Issue u fshi.');
    }

   
    public function attachTag(Request $request, Issue $issue)
    {
        $request->validate(['tag_id' => 'required|exists:tags,id']);
        $issue->tags()->syncWithoutDetaching([$request->tag_id]);
        return response()->json($issue->tags()->orderBy('name')->get());
    }

  
    public function detachTag(Issue $issue, Tag $tag)
    {
        $issue->tags()->detach($tag->id);
        return response()->json($issue->tags()->orderBy('name')->get());
    }

    public function attachMember(Request $request, Issue $issue)
    {
        $request->validate(['user_id' => 'required|exists:users,id']);
        $issue->members()->syncWithoutDetaching([$request->user_id]);
        return response()->json($issue->members()->orderBy('name')->get());
    }

    public function detachMember(Issue $issue, User $user)
    {
        $issue->members()->detach($user->id);
        return response()->json($issue->members()->orderBy('name')->get());
    }

    public function comments(Issue $issue)
    {
        return response()->json(
            $issue->comments()->latest()->paginate(5)
        );
    }

   
    public function addComment(Request $request, Issue $issue)
    {
        $validator = Validator::make($request->all(), [
            'author_name' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $comment = $issue->comments()->create($validator->validated());
        return response()->json($comment, 201);
    }
}