<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $post->title }}</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow sm:rounded-lg p-6">

                <div class="flex flex-col lg:flex-row gap-6">
                    <!-- Main tasks column -->
                    <main class="flex-1">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Tasks</h3>
                            <p class="text-sm text-gray-500">Tasks for this classroom</p>
                        </div>

                        <!-- Comment button (below the header) -->
                        

                        <div class="space-y-4">
                            @php
                                $list = $tasks ?? ($post->tasks ?? collect());
                            @endphp

                            @forelse($list as $task)
                                <div class="p-4 border rounded-md hover:shadow-sm transition">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h4 class="font-semibold text-gray-900">
                                                <a href="{{ route('posts.tasks.show', [$post, $task]) }}"
                                                   class="text-indigo-600 hover:underline">
                                                    {{ $task->title }}
                                                </a>
                                            </h4>
                                             @if(!empty($task->description))
                                                 <p class="text-sm text-gray-600 mt-1">{{ $task->description }}</p>
                                             @endif
                                        </div>

                                        <div class="text-xs text-gray-500 ml-4">
                                            {{ optional($task->created_at)->diffForHumans() }}
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="p-6 text-center text-gray-500 border rounded">
                                    No tasks yet. Click "New Task" to add one.
                                </div>
                            @endforelse
                        </div>
                    </main>

                    <!-- Right action box -->
                    <aside class="w-full lg:w-56">
                        <div class="p-4 bg-gray-50 border rounded-md sticky top-6">
                            <h4 class="text-sm font-semibold text-gray-800 mb-3">Actions</h4>

                            
                            <a href="/posts/{{ $post->id }}/tasks/create" 
                               class="block w-full text-center px-3 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">
                                New Task
                            </a>

                            <!-- Optional: quick helper -->
                            <p class="mt-3 text-xs text-gray-500">
                                Add tasks for teachers and students. Link the button to your task creation route or open a modal.
                            </p>
                        </div>
                    </aside>
                </div>
                

            </div>
            <div class="mb-4">
@auth
<form action="{{ route('posts.comments.store', $post) }}" method="POST" class="space-y-3">
    @csrf
    <input type="hidden" name="author_name" value="{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}   ">
    <label for="comment" class="block text-sm font-medium text-gray-700">Add a comment</label>
    <textarea id="comment" name="content" rows="3" required
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
        placeholder="Write your comment...">{{ old('content') }}</textarea>

    @error('content') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror

    <div class="flex items-center gap-3">
        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md">Post Comment</button>
    </div>
</form>
@endauth

        @foreach($post->comments as $comment)
            <div class="mt-4 p-4 border rounded-md">
                <div class="flex justify-between items-center mb-2">
                    <span class="font-semibold text-gray-900">{{ $comment->author_name }}</span>
                    <span class="text-xs text-gray-500">{{ $comment->created_at?->format('M j, Y \a\t g:i A') }}</span>
                </div>
                <p class="text-gray-800">{{ $comment->content }}</p>
            </div>
        @endforeach


</div>
        </div>
    </div>
</x-app-layout>