<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubmitTask;
use App\Models\Posts;
use App\Models\Task;
use App\Models\SubmissionGrade;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SubmitTaskController extends Controller
{
    // Store a student's submission (file + optional message)
    public function store(Request $request, Posts $post, Task $task)
    {
        $validated = $request->validate([
            'submission_file' => ['required','file','mimes:pdf,doc,docx,txt,zip','max:10240'],
            'message' => ['nullable','string','max:2000'],
        ]);

        $user = $request->user();
        $file = $request->file('submission_file');
        $path = $file->store('submissions', 'public');

        $submission = SubmitTask::where('task_id', $task->id)
            ->where('user_id', $user->id)
            ->first();

        if ($submission) {
            if ($submission->file_path && \Storage::disk('public')->exists($submission->file_path)) {
                \Storage::disk('public')->delete($submission->file_path);
            }

            $submission->update([
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'file_mime' => $file->getClientMimeType(),
                'file_size' => $file->getSize(),
                'message' => $validated['message'] ?? $submission->message,
            ]);
        } else {
            SubmitTask::create([
                'user_id'    => $user->id,
                'task_id'    => $task->id,
                'file_name'  => $file->getClientOriginalName(),
                'file_path'  => $path,
                'file_mime'  => $file->getClientMimeType(),
                'file_size'  => $file->getSize(),
                'message'    => $validated['message'] ?? null,
            ]);
        }

        // If current user is a teacher, send them to the submissions index.
        // Students return to the task/post page so they don't hit the teacher-only page.
        if (method_exists($user, 'hasRole') && $user->hasRole('teacher')) {
            return redirect()->route('posts.tasks.submissions.index', [$post, $task])
                ->with('success', 'Submission uploaded.');
        }

        return redirect()->route('posts.show', $post)->with('success', 'Submission uploaded.');
    }

    // unsubmit: delete the student's submission (and file)
    public function destroy(Request $request, Posts $post, Task $task)
    {
        $user = $request->user();

        $submission = SubmitTask::where('task_id', $task->id)
            ->where('user_id', $user->id)
            ->first();

        if (! $submission) {
            return back()->withErrors(['submission' => 'No submission found.']);
        }

        // delete stored file
        if ($submission->file_path && Storage::disk('public')->exists($submission->file_path)) {
            Storage::disk('public')->delete($submission->file_path);
        }

        // remove DB record so teacher cannot see it
        $submission->delete();

        return back()->with('success', 'Submission removed. You can submit a new file now.');
    }

    // list submissions for a task (teacher view)
    public function index(Request $request, Posts $post, Task $task)
    {
        if ($task->post_id !== $post->id) {
            abort(404);
        }

        $user = $request->user();
        $isTeacher = method_exists($user, 'hasRole') ? $user->hasRole('teacher') : (($user->role ?? null) === 'teacher');
        if (! $isTeacher) {
            abort(403);
        }

        $submissions = SubmitTask::where('task_id', $task->id)->with('user')->latest()->get();

        // Use dot notation for view name and point to resources/views/tasks/submissions/index.blade.php
        return view('tasks.submissions.index', compact('post', 'task', 'submissions'));
    }

    // show grade form for a single submission
    public function gradeForm(Request $request, Posts $post, Task $task, SubmitTask $submission)
    {
        if ($task->post_id !== $post->id || $submission->task_id !== $task->id) {
            abort(404);
        }

        $user = $request->user();
        $isTeacher = method_exists($user, 'hasRole') ? $user->hasRole('teacher') : (($user->role ?? null) === 'teacher');
        if (! $isTeacher) {
            abort(403);
        }

        // load existing grade if any
        $grade = SubmissionGrade::where('submit_task_id', $submission->id)->first();

        return view('tasks.grade', compact('post', 'task', 'submission', 'grade'));
    }

    // store grade + feedback -> uses submission_grades table
    public function updateGrade(Request $request, Posts $post, Task $task, SubmitTask $submission)
    {
        if ($task->post_id !== $post->id || $submission->task_id !== $task->id) {
            abort(404);
        }

        $user = $request->user();
        $isTeacher = method_exists($user, 'hasRole') ? $user->hasRole('teacher') : (($user->role ?? null) === 'teacher');
        if (! $isTeacher) {
            abort(403);
        }

        $validated = $request->validate([
            'grade' => ['nullable','numeric','between:0,100'],
            'feedback' => ['nullable','string','max:5000'],
        ]);

        $grade = SubmissionGrade::updateOrCreate(
            ['submit_task_id' => $submission->id],
            [
                'grader_id' => $user->id,
                'grade' => $validated['grade'] ?? null,
                'feedback' => $validated['feedback'] ?? null,
                'graded_at' => now(),
            ]
        );

        return redirect()->route('posts.tasks.submissions.index', [$post, $task])
            ->with('success', 'Submission graded.');
    }
}
