
<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Join Classroom Section (Students only) -->
            @unless (auth()->user()->hasRole('teacher'))
            <div class="mb-8 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">{{ __('Join a Classroom') }}</h3>
                    
                    @if ($errors->has('access_code'))
                        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                            <p class="text-red-800 text-sm">{{ $errors->first('access_code') }}</p>
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                            <p class="text-green-800 text-sm">{{ session('success') }}</p>
                        </div>
                    @endif

                    <form action="{{ route('classrooms.join') }}" method="POST" class="flex gap-2">
                        @csrf
                        <input
                            type="text"
                            name="access_code"
                            placeholder="Enter 6-character code"
                            maxlength="6"
                            value="{{ old('access_code') }}"
                            class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 uppercase"
                        />
                        <button
                            type="submit"
                            class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors"
                        >
                            {{ __('Join') }}
                        </button>
                    </form>
                </div>
            </div>
            @endunless

            <!-- Classrooms Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @role('teacher')
                    <div class="mb-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold">My Classrooms</h3>
                            <a href="{{ route('classrooms.create') }}" class="inline-flex items-center justify-center w-10 h-10 bg-blue-600 hover:bg-blue-700 text-white rounded-full transition-colors text-xl font-bold" title="Create new classroom">
                                +
                            </a>
                        </div>
                        
                        @php
                            $myClassrooms = \App\Models\Classroom::where('teacher_id', auth()->id())->get();
                        @endphp

                        @if($myClassrooms->isEmpty())
                            <p class="text-gray-500">No classrooms created yet.</p>
                        @else
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($myClassrooms as $classroom)
                                    <a href="{{ route('classrooms.show', $classroom) }}" class="block border rounded-lg p-4 hover:shadow-lg hover:border-blue-400 transition-all duration-200 cursor-pointer bg-white">
                                        <h4 class="font-semibold text-gray-900 mb-2">{{ $classroom->name }}</h4>
                                        <p class="text-sm text-gray-600 mb-3">{{ $classroom->description }}</p>
                                        <p class="text-xs text-gray-500 mb-3">
                                            <strong>Code:</strong> <span class="font-mono bg-gray-100 px-2 py-1 rounded">{{ $classroom->access_code }}</span>
                                        </p>
                                        <span class="inline-block px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded transition-colors">
                                            View Classroom →
                                        </span>
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    @endrole

                    @unless (auth()->user()->hasRole('teacher'))
                    @php
                        $classrooms = auth()->user()->enrolledClassrooms()->get();
                    @endphp

                    @if($classrooms->isNotEmpty())
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-4">My Classrooms</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($classrooms as $classroom)
                                <a href="{{ route('classrooms.show', $classroom) }}" class="block border rounded-lg p-4 hover:shadow-lg hover:border-blue-400 transition-all duration-200 cursor-pointer bg-white">
                                    @if($classroom->cover_image)
                                        <img src="{{ asset('storage/' . $classroom->cover_image) }}" alt="{{ $classroom->name }}" class="w-full h-40 object-cover rounded mb-3">
                                    @endif
                                    <h4 class="font-semibold text-gray-900 mb-2">{{ $classroom->name }}</h4>
                                    <p class="text-sm text-gray-600 mb-3">{{ $classroom->description }}</p>
                                    <span class="inline-block px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded transition-colors">
                                        View Classroom →
                                    </span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    @endunless
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
