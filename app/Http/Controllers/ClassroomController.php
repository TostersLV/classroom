<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Posts;

class ClassroomController extends Controller
{
    /**
     * Join a classroom by code (stores joined post ids in session).
     */
    public function join(Request $request)
    {
        $request->validate([
            'code' => ['required','string','max:12'],
        ]);

        $code = strtoupper($request->input('code'));
        $post = Posts::where('code', $code)->first();

        if (! $post) {
            return back()->withErrors(['code' => 'Classroom code not found.'])->withInput();
        }

        $user = $request->user();
        if (! $user) {
            return back()->withErrors(['code' => 'You must be logged in to join.'])->withInput();
        }

        // attach in DB (no duplicates)
        $user->joinedPosts()->syncWithoutDetaching([$post->id]);

        // also clear any session fallback
        $joined = session('joined_posts', []);
        if (in_array($post->id, $joined, true)) {
            // remove from session now that it's persisted
            session(['joined_posts' => array_values(array_diff($joined, [$post->id]))]);
        }

        return redirect()->route('posts.show', $post)->with('success', 'Joined classroom.');
    }
}
