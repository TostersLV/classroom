<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Submissions — {{ $task->title }}</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow sm:rounded-lg p-6">
                <a href="{{ route('posts.show', $post) }}" class="inline-block mb-4 text-sm px-3 py-2 bg-gray-100 rounded">Back to Classroom</a>

                @if($submissions->isEmpty())
                    <p class="text-sm text-gray-600">No submissions yet.</p>
                @else
                    <ul class="space-y-4">
                        @foreach($submissions as $submission)
                            <li class="p-4 border rounded flex items-start justify-between">
                                <div>
                                    <div class="font-medium">{{ $submission->user->name ?? 'Student' }}</div>
                                    <div class="text-sm text-gray-600">
                                        @if($submission->file_path)
                                            <a href="{{ asset('storage/'.$submission->file_path) }}" target="_blank" class="text-indigo-600 underline">{{ $submission->file_name }}</a>
                                        @else
                                            <span class="text-gray-500">No file</span>
                                        @endif
                                    </div>
                                    @if(!empty($submission->message))
                                        <div class="text-sm text-gray-700 mt-1">{{ $submission->message }}</div>
                                    @endif
                                    <div class="text-xs text-gray-500 mt-2">Submitted: {{ $submission->created_at->format('M j, Y \\a\\t g:i A') }}</div>
                                </div>

                                <div class="flex flex-col items-end gap-2">
                                    <a href="{{ route('posts.tasks.submissions.grade', [$post, $task, $submission]) }}" class="px-3 py-1 bg-indigo-600 text-white rounded text-sm">Grade</a>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>