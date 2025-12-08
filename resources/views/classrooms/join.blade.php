<x-app-layout>
    <div class="py-12">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Join a Classroom</h2>

                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                            <p class="text-red-800 font-semibold mb-2">Unable to join classroom:</p>
                            <ul class="text-red-700 text-sm list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('classrooms.join') }}" method="POST">
                        @csrf

                        <div class="mb-6">
                            <label for="access_code" class="block text-sm font-medium text-gray-700 mb-2">
                                Classroom Access Code
                            </label>
                            <input
                                type="text"
                                id="access_code"
                                name="access_code"
                                placeholder="Enter 6-character code"
                                maxlength="6"
                                value="{{ old('access_code') }}"
                                class="w-full px-4 py-2 border @error('access_code') border-red-500 @else border-gray-300 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 uppercase"
                                autofocus
                            />
                            @error('access_code')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-2 text-xs text-gray-500">
                                Ask your teacher for the 6-character classroom code
                            </p>
                        </div>

                        <button
                            type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors duration-200"
                        >
                            Join Classroom
                        </button>
                    </form>

                    <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <p class="text-sm text-blue-800">
                            <strong>Don't have a code?</strong> Contact your teacher to get the classroom access code.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
