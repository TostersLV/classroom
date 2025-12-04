<?php

namespace App\Http\Controllers;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Models\Posts;

class CommentController extends Controller
{
    public function index(){
        $comments = Comment::all();
        return view('posts.show', compact('comments'));
    }
    public function store(Request $request, Posts $post)    // <- add Posts $post
    {
        $validated = $request->validate([
        'content' => ['required','string','max:2000'],
        'author_name' => ['string','max:255']
    ]);

    $post->comments()->create([
        'content'     => $validated['content'],
        'user_id'     => $request->user()->id,
        'author_name' => $validated['author_name'],
    ]);

        return redirect()->route('posts.show', $post)->with('success', 'Comment posted.');
    }
    public function destroy(Comment $comment){
        $comment->delete();
        return redirect()->back();
    }
    
}
