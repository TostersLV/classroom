<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Grade Submission</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow sm:rounded-lg p-6">
                <div class="mb-4">
                    <div class="font-medium">{{ $submission->user->name ?? 'Student' }}</div>
                    <div class="text-sm text-gray-600">{{ $submission->created_at?->format('M d, Y H:i') }}</div>
                </div>

                @if(!empty($submission->file_path))
                    <div class="mb-4">
                        <a href="{{ asset('storage/'.$submission->file_path) }}" target="_blank" class="px-3 py-2 bg-gray-100 rounded">Download submitted file</a>
                    </div>
                @endif

                <form action="{{ route('posts.tasks.submissions.update', [$post, $task, $submission]) }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Grade (0-100)</label>
                        <input name="grade" type="number" step="0.01" min="0" max="100" value="{{ old('grade', $grade->grade ?? null) }}" class="mt-1 block w-full rounded border-gray-300 px-3 py-2" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Feedback</label>
                        <textarea name="feedback" rows="5" class="mt-1 block w-full rounded border-gray-300 px-3 py-2">{{ old('feedback', $grade->feedback ?? null) }}</textarea>
                    </div>

                    <div class="flex items-center gap-3">
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Save Grade</button>
                        <a href="{{ route('posts.tasks.submissions.index', [$post, $task]) }}" class="px-4 py-2 bg-gray-100 rounded">Back</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>