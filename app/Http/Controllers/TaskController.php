<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Posts;

class TaskController extends Controller
{
    public function index(){
        $tasks = Task::all();
        return view('tasks.index', compact('tasks'));
    }
    public function show(Posts $post, Task $task)
    {
        // ensure the task belongs to the post (optional safety check)
        if ($task->post_id !== $post->id) {
            abort(404);
        }

        return view('tasks.show', compact('post', 'task'));
    }
    public function create(Posts $post)
    {
        return view('tasks.create', compact('post'));
    }

    // minimal fix: accept null Posts, ensure post_id is set, create via relation when possible
    public function store(Request $request, Posts $post = null)
    {
        $validated = $request->validate([
            'title' => ['required','string','max:255'],
            'task_description' => ['nullable','string'],
            'author_name' => ['nullable','string','max:255'],
            'attachment' => ['nullable','file','mimes:pdf,doc,docx,txt,zip','max:10240'], // 10MB
            // optional post_id in request if route does not provide Posts $post
            'post_id' => ['nullable','exists:posts,id'],
        ]);

        $user = $request->user();

        $data = [
            'user_id' => $user?->id,
            'author_name' => $validated['author_name'] ?? $user?->name ?? 'Unknown',
            'title' => $validated['title'],
            'task_description' => $validated['task_description'] ?? null,
        ];

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $path = $file->store('task-files', 'public');
            $data['file_name'] = $file->getClientOriginalName();
            $data['file_path'] = $path;
            $data['file_mime'] = $file->getClientMimeType();
            $data['file_size'] = $file->getSize();
        }

        // determine post id (prefer route-model bound $post)
        $postId = $post?->id ?? $validated['post_id'] ?? $request->input('post_id');

        if (!$postId) {
            return back()->withErrors(['post_id' => 'Post ID is required'])->withInput();
        }

        if ($post) {
            // create via relation so post_id is set automatically
            $task = $post->tasks()->create($data);
        } else {
            // create directly and include post_id
            $data['post_id'] = $postId;
            $task = Task::create($data);
        }

        return redirect()->route('posts.show', $postId)->with('success', 'Task created.');
    }

}
