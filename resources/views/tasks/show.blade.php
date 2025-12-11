<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $task->title }}</h2>
    </x-slot>

    <div class="py-6">  
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow sm:rounded-lg p-6">

                <!-- layout: main content + right submit box -->
                <div class="flex flex-col lg:flex-row gap-6">
                    <main class="flex-1">
                        <!-- ...existing code... (task details) -->
                        <div class="mb-4 flex items-start justify-between">
                            <div>
                                <p class="text-sm text-gray-600">Author: <span class="font-medium">{{ $task->author_name ?? 'Unknown' }}</span></p>
                                <p class="text-sm text-gray-500">Created: {{ optional($task->created_at)->format('M d, Y H:i') }}</p>
                            </div>

                            <div class="text-right">
                                <a href="{{ route('posts.show', $post) }}" class="inline-flex px-3 py-2 bg-gray-100 text-sm rounded-md hover:bg-gray-200">Back to Classroom</a>
                            </div>
                        </div>

                        <h3 class="text-lg font-semibold mb-2">Description</h3>
                        <div class="prose max-w-none mb-6">
                            @if(!empty($task->task_description))
                                <p>{{ $task->task_description }}</p>
                            @else
                                <p class="text-sm text-gray-500">No description provided.</p>
                            @endif
                        </div>

                        @if(!empty($task->file_path) || !empty($task->file_name))
                            <div class="mb-6">
                                <h4 class="text-sm font-medium text-gray-800 mb-2">Attachment</h4>
                                <div class="flex items-center gap-3">
                                    <div class="text-sm text-gray-700">
                                        {{ $task->file_name ?? basename($task->file_path) }}
                                        @if(!empty($task->file_size))
                                            <span class="text-xs text-gray-500">({{ number_format($task->file_size) }} bytes)</span>
                                        @endif
                                    </div>

                                    @if(!empty($task->file_path))
                                        <a href="{{ asset('storage/'.$task->file_path) }}" target="_blank" rel="noopener" class="inline-flex items-center px-3 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                            View / Download
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- optional actions -->
                        <div class="flex gap-3">
                            @can('update', $task)
                                <a href="{{ route('posts.tasks.edit', [$post, $task]) }}" class="px-3 py-2 bg-yellow-100 text-sm rounded-md">Edit</a>
                            @endcan

                            @can('delete', $task)
                                <form action="{{ route('posts.tasks.destroy', [$post, $task]) }}" method="POST" onsubmit="return confirm('Delete this task?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-2 bg-red-100 text-sm rounded-md">Delete</button>
                                </form>
                            @endcan
                        </div>

                        <!-- Teacher-only: View submissions / grade button -->
                        @php
                            $user = auth()->user();
                            $isTeacher = $user ? (method_exists($user,'hasRole') ? $user->hasRole('teacher') : (($user->role ?? null) === 'teacher')) : false;
                        @endphp

                        @if($isTeacher)
                            <div class="mt-6">
                                <a href="{{ route('posts.tasks.submissions.index', [$post, $task]) }}" class="inline-flex items-center px-3 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                    View Submissions & Grade
                                </a>
                            </div>
                        @endif

                        <!-- comments: form + list -->
                        <section class="mt-8">
                            <h3 class="text-lg font-semibold mb-3">Comments</h3>

                            @auth
                                <form action="{{ route('posts.tasks.comments.store', [$post, $task]) }}" method="POST" class="mb-4">
                                    @csrf
                                    <textarea name="body" rows="3" class="comment-textarea w-full p-3 rounded-md border bg-white dark:bg-slate-800 dark:text-slate-100" placeholder="Write your comment..." required>{{ old('body') }}</textarea>
                                    @error('body') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                                    <div class="mt-2">
                                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md">Post Comment</button>
                                    </div>
                                </form>
                            @else
                                <p class="text-sm text-gray-500">Please <a href="{{ route('login') }}" class="text-indigo-600 underline">log in</a> to comment.</p>
                            @endauth

                            <div class="space-y-4">
                                @foreach($task->comments as $comment)
                                    @php
                                        $author = $comment->user;
                                        $avatar = $author?->profile_picture_url ?? asset('images/default-avatar.png');
                                    @endphp

                                    <article class="comment-card p-4 rounded-md border flex items-start gap-4">
                                        <img src="{{ $avatar }}" alt="{{ $author?->first_name ?? 'User' }}" class="w-12 h-12 rounded-full object-cover avatar" />

                                        <div class="flex-1">
                                            <div class="flex items-center justify-between">
                                                <div class="font-medium author-name text-gray-900 dark:text-slate-100">
                                                    {{ $author?->first_name ?? $author?->name ?? 'Unknown' }}
                                                </div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                                    {{ $comment->created_at?->format('M j, Y \a\t g:i A') }}
                                                </div>
                                            </div>

                                            <div class="mt-2 comment-body">
                                                {{ $comment->body }}
                                            </div>
                                        </div>
                                    </article>
                                @endforeach
                            </div>
                        </section>

                        <!-- end main content -->
                    </main>

                    <!-- Right: submission box -->
                    @php
                        $submission = auth()->check()
                            ? \App\Models\SubmitTask::where('task_id', $task->id)
                                ->where('user_id', auth()->id())
                                ->with('grade') // eager-load grade
                                ->first()
                            : null;
                    @endphp

                    <aside class="w-full lg:w-80">
                        <div class="p-4 bg-gray-50 border rounded-md sticky top-6">
                            <h4 class="text-sm font-semibold text-gray-800 mb-3">Submit your work</h4>

                            @auth
                                @if(!$submission)
                                    <form action="{{ route('posts.tasks.submissions.store', [$post, $task]) }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                                        @csrf
                                        <input type="file" name="submission_file" required class="block w-full" />
                                        <textarea name="message" rows="3" class="w-full" placeholder="Optional comment"></textarea>
                                        <button type="submit" class="w-full px-3 py-2 bg-indigo-600 text-white rounded">Submit Work</button>
                                    </form>
                                @else
                                    <div class="p-3 bg-white rounded border">
                                        @php
                                            // safe helpers
                                            $fileUrl = $submission->file_path ? Storage::disk('public')->url($submission->file_path) : null;
                                            $fileName = $submission->file_name ?? ($submission->file_path ? basename($submission->file_path) : 'Uploaded file');
                                            $submittedAtRaw = $submission->created_at ?? null;
                                            $submittedAt = $submittedAtRaw ? \Illuminate\Support\Carbon::parse($submittedAtRaw) : null;
                                        @endphp

                                        @if($fileUrl)
                                            <a href="{{ $fileUrl }}" target="_blank" class="text-indigo-600 underline">
                                                {{ $fileName }}
                                            </a>
                                        @else
                                            <div class="text-indigo-600 font-medium">{{ $fileName }}</div>
                                        @endif

                                        <div class="text-xs text-gray-500 mt-2">
                                            Submitted: {{ $submittedAt ? $submittedAt->format('M j, Y \a\t g:i A') : '—' }}
                                        </div>

                                        @if($submission->grade)
                                            <div class="mt-3 p-2 bg-blue-50 border rounded text-sm">
                                                <div><strong>Grade:</strong> {{ $submission->grade->grade ?? '—' }} @if($submission->grade->grade !== null) /100 @endif</div>

                                                @if(!empty($submission->grade->feedback))
                                                    <div class="text-sm text-gray-700 mt-1"><strong>Feedback:</strong> {{ $submission->grade->feedback }}</div>
                                                @endif

                                                @php
                                                    $gradedRaw = $submission->grade->graded_at ?? $submission->grade->created_at ?? null;
                                                    $gradedAt = $gradedRaw ? \Illuminate\Support\Carbon::parse($gradedRaw) : null;
                                                @endphp

                                                <div class="text-xs text-gray-500 mt-2">
                                                    Graded: {{ $gradedAt ? $gradedAt->format('M j, Y \a\t g:i A') : '—' }}
                                                </div>
                                            </div>
                                        @else
                                            <div class="mt-3">
                                                <form action="{{ route('posts.tasks.submissions.unsubmit', [$post, $task]) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="w-full px-3 py-2 bg-gray-600 text-white rounded">Unsubmit</button>
                                                </form>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            @endauth

                            @guest
                                <p class="text-sm text-gray-500">Please <a href="{{ route('login') }}">log in</a> to submit work.</p>
                            @endguest

                            <p class="mt-3 text-xs text-gray-500">Submissions are visible to the teacher.</p>
                        </div>
                    </aside>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>