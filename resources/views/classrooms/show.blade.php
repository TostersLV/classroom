<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if ($classroom->cover_image)
                        <img src="{{ asset('storage/' . $classroom->cover_image) }}" alt="{{ $classroom->name }}" class="w-full h-64 object-cover rounded-lg mb-6">
                    @endif

                    <h2 class="text-3xl font-bold text-gray-900 mb-2">{{ $classroom->name }}</h2>
                    
                    @if ($classroom->isTeacher())
                        <div class="flex gap-2 mb-4">
                            <a href="{{ route('classrooms.edit', $classroom) }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded transition-colors">
                                Edit
                            </a>
                            <form action="{{ route('classrooms.destroy', $classroom) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm rounded transition-colors" onclick="return confirm('Are you sure?')">
                                    Delete
                                </button>
                            </form>
                        </div>
                    @endif
                    
                    @if ($classroom->description)
                        <p class="text-gray-600 mb-4">{{ $classroom->description }}</p>
                    @endif

                    <!-- Show access code only to teacher -->
                    @if ($classroom->isTeacher())
                        <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <p class="text-sm text-gray-600 mb-2">
                                <strong>Classroom Access Code (share with students):</strong>
                            </p>
                            <div class="flex items-center gap-2">
                                <code class="text-2xl font-mono font-bold text-yellow-700 bg-white px-4 py-2 rounded border border-yellow-300">
                                    {{ $classroom->access_code }}
                                </code>
                                <button
                                    onclick="navigator.clipboard.writeText('{{ $classroom->access_code }}')"
                                    class="px-3 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm rounded transition-colors"
                                >
                                    Copy
                                </button>
                            </div>
                        </div>
                    @endif

                    <!-- Student count and list -->
                    <div class="mt-8">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">
                            Enrolled Students ({{ count($students) }})
                        </h3>

                        @if (count($students) > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach ($students as $student)
                                    <div class="p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                        <p class="font-semibold text-gray-900">
                                            {{ $student->first_name }} {{ $student->last_name }}
                                        </p>
                                        <p class="text-sm text-gray-600">{{ $student->email }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 italic">No students enrolled yet</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
