<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Posts;
use App\Models\Comment;
use App\Models\Task;
use Illuminate\Support\Str;


class PostsController extends Controller
{
    /**
     * Show the form for creating a new classroom/post.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $isTeacher = $user && (method_exists($user, 'hasRole') ? $user->hasRole('teacher') : (($user->role ?? null) === 'teacher'));

        if ($isTeacher) {
            $posts = Posts::latest()->get();
        } else {
            $posts = $user ? $user->joinedPosts()->latest('posts.created_at')->get() : collect();
        }

        return view('posts.index', compact('posts'));
    }

    public function show(Posts $post, Comment $comment, Task $task)
    {
        $comment  = Comment::all();
        $tasks = Task::all();
        return view('posts.show', compact('post', 'comment'));
    }


    public function create(Posts $posts)
    {
        return view('posts.create', compact('posts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => ['required','string','max:255'],
            'author'      => ['required','string','max:255'],
            'cover_image' => ['nullable','image','mimes:jpg,jpeg,png,gif','max:5120'],
        ]);


         if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('classroom-covers', 'public');
            $validated['cover_image'] = $path;
        }

        // associate with current teacher
        $validated['user_id'] = $request->user()->id;

        
        Posts::create([
            "title" => $validated['title'],
            "author" => $validated['author'],
            "cover_image" => $validated['cover_image'] ?? null,
            "user_id" => $validated['user_id'],
        ]);

        return redirect("/dashboard");
    }
    public function generateCode(Request $request, Posts $post)
    {
        // only generate if missing (safe-guard)
        if (empty($post->code)) {
            do {
                $code = strtoupper(Str::random(6));
            } while (Posts::where('code', $code)->exists());

            $post->code = $code;
            $post->save();
        }

        return redirect()->back()->with('success', 'Classroom code generated.');
    }
}
