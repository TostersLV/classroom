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
                        <!-- end main content -->
                    </main>

                    <!-- Right: submission box -->
                    <aside class="w-full lg:w-80">
                        <div class="p-4 bg-gray-50 border rounded-md sticky top-6">
                            <h4 class="text-sm font-semibold text-gray-800 mb-3">Submit your work</h4>

                            @auth
                                <form action="{{ route('posts.tasks.submissions.store', [$post, $task]) }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                                    @csrf

                                    <div>
                                        <label for="submission_file" class="block text-sm font-medium text-gray-700">File</label>
                                        <input id="submission_file" name="submission_file" type="file" required
                                               accept=".pdf,.doc,.docx,.zip,.txt"
                                               class="mt-1 block w-full text-sm text-gray-700" />
                                        <p class="mt-1 text-xs text-gray-500">Allowed: pdf, doc, docx, txt, zip. Max ~10MB.</p>
                                        @error('submission_file') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                                    </div>

                                    <div>
                                        <label for="message" class="block text-sm font-medium text-gray-700">Comment (optional)</label>
                                        <textarea id="message" name="message" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('message') }}</textarea>
                                        @error('message') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                                    </div>

                                    <div class="flex items-center gap-3">
                                        <button type="submit" class="w-full text-center px-3 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                            Submit Work
                                        </button>
                                    </div>
                                </form>
                            @else
                                <p class="text-sm text-gray-500">Please <a href="{{ route('login') }}" class="text-indigo-600 underline">log in</a> to submit your work.</p>
                            @endauth

                            <p class="mt-3 text-xs text-gray-500">Submissions are visible to the teacher. Contact them if you have issues.</p>
                        </div>
                    </aside>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>