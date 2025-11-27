
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Classrooms') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <div class="text-base font-medium text-gray-900">
                            {{ __("You're logged in!") }}
                        </div>

                        @role('teacher')
                            <a href="{{ route('posts.create') }}" class="inline-flex items-center px-3 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">
                                {{ __('New Classroom') }}
                            </a>
                        @endrole
                    </div>

                    @php
                        $classrooms = $classrooms ?? (auth()->check() ? auth()->user()->posts ?? collect() : collect());
                    @endphp

                    @if($classrooms->isEmpty())
                        <div class="border rounded-lg p-6 text-center text-gray-500">
                            {{ __('No classrooms yet. Create one to get started.') }}
                        </div>
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($classrooms as $post)
                                <div class="bg-white border rounded-lg overflow-hidden shadow-sm">
                                    @if(!empty($post->cover_image) && file_exists(storage_path('app/public/'.$post->cover_image)))
                                        <img src="{{ asset('storage/'.$post->cover_image) }}" alt="{{ $post->title }}" class="w-full h-40 object-cover">
                                    @else
                                        <div class="w-full h-40 bg-gray-100 flex items-center justify-center text-gray-400">
                                            {{ __('No image') }}
                                        </div>
                                    @endif

                                    <div class="p-4">
                                        <h3 class="font-semibold text-lg text-gray-900 truncate">{{ $post->title }}</h3>
                                        @if(!empty($post->subject))
                                            <p class="text-sm text-gray-600 mt-1">{{ $post->subject }}</p>
                                        @endif

                                        <div class="mt-3 flex items-center justify-between text-sm text-gray-500">
                                            <span>{{ __('By') }} {{ $post->author ?? ($post->user->first_name ?? auth()->user()->first_name ?? '') }}</span>
                                            <span>{{ $post->created_at?->format('M j, Y') }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
