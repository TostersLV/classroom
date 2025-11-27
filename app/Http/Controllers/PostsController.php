<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Posts;

class PostsController extends Controller
{
    /**
     * Show the form for creating a new classroom/post.
     */
    public function index()
    {
        $posts = Posts::latest()->get();

        return view('posts.index', compact('posts'));
    }

    public function show(Posts $post)
    {
        return view('posts.show', compact('post'));
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
}
