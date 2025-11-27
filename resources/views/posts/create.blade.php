<x-app-layout>
    <x-slot:title>
        Create New Classroom
    </x-slot:title>


<div class="container mx-auto px-4 py-8 max-w-3xl">
    <div class="bg-white rounded-lg shadow p-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-4">Create New Classroom</h1>

        <form method="POST" action="/posts" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div>
                <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                <input id="title" name="title" type="text" value="{{ old('title') }}" required
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                @error('title') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="author" class="block text-sm font-medium text-gray-700">Author</label>
                <input id="author" name="author" type="text"
                       value="{{ old('author', isset(auth()->user()->first_name) ? auth()->user()->first_name.' '.auth()->user()->last_name : (auth()->user()->name ?? '')) }}"
                       required
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                @error('author') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="cover_image" class="block text-sm font-medium text-gray-700">Cover image (optional)</label>
                <input id="cover_image" name="cover_image" type="file" accept="image/*"
                       class="mt-1 block w-full text-sm text-gray-600">
                @error('cover_image') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">
                    Create Classroom
                </button>
                <a href="{{ route('dashboard') }}" class="px-4 py-2 border rounded-md text-gray-700 hover:bg-gray-50">Cancel</a>
            </div>
        </form>
    </div>
</div>
</x-app-layout>