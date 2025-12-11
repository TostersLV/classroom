<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskCommentController extends Controller
{
    public function store(Request $request, $post, Task $task)
    {
        $data = $request->validate(['body' => 'required|string|max:2000']);

        $task->comments()->create([
            'user_id' => $request->user()->id,
            'body' => $data['body'],
        ]);

        return back()->with('status', 'comment-posted');
    }
}
