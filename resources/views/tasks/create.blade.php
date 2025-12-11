<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">New Task</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                @if(session('success'))
                    <div class="mb-4 text-sm text-green-700">{{ session('success') }}</div>
                @endif

                @if($errors->any())
                    <div class="mb-4 text-sm text-red-700">
                        <ul class="list-disc ml-5">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- create form: posts.tasks.store expects a Posts $post via route-model binding -->
                <form action="/tasks" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <input type="hidden" value=" {{ $post->id }}" name="post_id"     />
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                        <input id="title" name="title" value="{{ old('title') }}" required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" />
                    </div>

                    <!-- no need to submit author_name if you set it server-side; keep if you want override -->
                    <input type="hidden" name="author_name" value="{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}">

                    <div>
                        <label for="task_description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea id="task_description" name="task_description" rows="5"
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('task_description') }}</textarea>
                    </div>

                    <div>
                        <label for="attachment" class="block text-sm font-medium text-gray-700">Attachment (optional)</label>
                        <input id="attachment" name="attachment" type="file" accept=".pdf,.doc,.docx,.txt,.zip"
                               class="mt-1 block w-full text-sm text-gray-700" />
                        <p class="mt-1 text-xs text-gray-500">Allowed: pdf, doc, docx, txt, zip. Max ~10MB (server-side enforced).</p>
                    </div>

                    <div class="flex items-center gap-3">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">
                            Create Task
                        </button>

                        <a href="" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-800 rounded-md hover:bg-gray-200 transition">
                            Cancel
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
