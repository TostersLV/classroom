<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Submissions — {{ $task->title }}</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if(session('success')) <div class="mb-4 text-sm text-green-600">{{ session('success') }}</div> @endif

            <div class="bg-white shadow sm:rounded-lg p-6">
                @forelse($submissions as $s)
                    <div class="border-b py-3 flex justify-between items-center">
                        <div>
                            <div class="font-medium">{{ $s->user->name ?? 'Student' }}</div>
                            <div class="text-sm text-gray-600">{{ $s->created_at?->format('M d, Y H:i') }}</div>
                            @if($s->message)
                                <div class="mt-2 text-sm">{{ $s->message }}</div>
                            @endif
                        </div>

                        <div class="flex items-center gap-3">
                            @if(!empty($s->file_path))
                                <a href="{{ asset('storage/'.$s->file_path) }}" target="_blank" class="px-3 py-2 bg-gray-100 rounded text-sm">Download</a>
                            @endif

                            <a href="{{ route('posts.tasks.submissions.grade', [$post, $task, $s]) }}" class="px-3 py-2 bg-indigo-600 text-white rounded text-sm">Grade</a>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-600">No submissions yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>