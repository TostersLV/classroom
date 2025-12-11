<x-app-layout>

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Classrooms') }}</h2>
@endsection

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <div></div>

                        @role('teacher')
                            <a href="/posts/create" class="inline-flex items-center px-3 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">
                                New Classroom
                            </a>
                        @endrole
                    </div>

                    <div class="lg:flex lg:items-start lg:space-x-6">
                        <!-- Main: classroom grid / empty message -->
                        <main class="flex-1">
                            @if($posts->isEmpty())
                                <div class="border rounded-lg p-6 text-center text-gray-500">
                                    @role('teacher')
                                        <p>{{ __('No classrooms yet. Create one to get started.') }}</p>
                                    @else
                                        <p class="mb-4">{{ __("You haven't joined any classrooms yet. Use the form on the right to join by code.") }}</p>

                                        @if(session('success'))
                                            <div class="mb-3 text-sm text-green-600">{{ session('success') }}</div>
                                        @endif

                                        @if($errors->has('code'))
                                            <div class="mb-2 text-sm text-red-600">{{ $errors->first('code') }}</div>
                                        @endif
                                    @endrole
                                </div>
                            @else
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                                    @foreach($posts as $post)
                                        <div class="bg-white border rounded-lg overflow-hidden shadow-sm">
                                            @if(!empty($post->cover_image) && file_exists(storage_path('app/public/'.$post->cover_image)))
                                                <img src="{{ asset('storage/'.$post->cover_image) }}" alt="{{ $post->title }}" class="w-full h-40 object-cover">
                                            @else
                                                @php
                                                    $colors = ['#FDE8E8','#E8FDF5','#E8EEF9','#F8E8FB','#FFF7E8','#E8FFF3','#FDEFE8'];
                                                    $color = $colors[$post->id % count($colors)];
                                                @endphp
                                                <div class="w-full h-40 flex items-center justify-center text-gray-700" style="background-color: {{ $color }};">
                                                </div>
                                            @endif

                                            <div class="p-4">
                                                <h3 class="font-semibold text-lg text-gray-900 truncate">
                                                    <a href="{{ route('posts.show', $post) }}">{{ $post->title }}</a>
                                                </h3>
                                                @if(!empty($post->subject))
                                                    <p class="text-sm text-gray-600 mt-1">{{ $post->subject }}</p>
                                                @endif

                                                <div class="mt-3 flex items-center justify-between text-sm text-gray-500">
                                                    <span>{{ __('By') }} {{ $post->author }}</span>
                                                    <span>{{ $post->created_at?->format('M j, Y') }}</span>
                                                </div>

                                                @role('teacher')
                                                    <div class="mt-2 text-sm">
                                                        @if(!empty($post->code))
                                                            <span class="inline-block px-2 py-1 bg-gray-100 rounded-md text-xs font-medium">Code: {{ $post->code }}</span>
                                                        @else
                                                            <form action="{{ route('posts.generate_code', $post) }}" method="POST" class="inline-block">
                                                                @csrf
                                                                <button type="submit" class="inline-flex items-center px-2 py-1 bg-indigo-600 text-white rounded-md text-xs hover:bg-indigo-700">
                                                                    Generate code
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                @endrole
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </main>

                        <!-- Right: persistent join box (always visible on the right) -->
                        @role('teacher')
                            {{-- Teachers do not see the join box --}}
                        @else
                            <aside class="mt-6 lg:mt-0 w-full lg:w-80">
                                <div class="p-4 bg-gray-50 border rounded-md sticky top-6">
                                    <h4 class="text-sm font-semibold text-gray-800 mb-3">Join Classroom</h4>

                                    @php
                                        $joined = session('joined_posts', []);
                                        $joinedPosts = !empty($joined) ? \App\Models\Posts::whereIn('id', $joined)->get() : collect();
                                    @endphp

                                    @if($joinedPosts->isNotEmpty())
                                        <div class="mb-3 text-sm">
                                            <div class="font-medium text-gray-700 mb-1">Joined</div>
                                            <ul class="text-sm text-gray-600 list-disc list-inside">
                                                @foreach($joinedPosts as $jp)
                                                    <li><a href="{{ route('posts.show', $jp) }}" class="text-indigo-600 hover:underline">{{ $jp->title }}</a></li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    <form action="{{ route('classroom.join') }}" method="POST" class="space-y-3">
                                        @csrf
                                        <div>
                                            <label for="code" class="block text-sm font-medium text-gray-700">Classroom code</label>
                                            <input id="code" name="code" value="{{ old('code') }}" required maxlength="12"
                                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm" placeholder="Enter code" />
                                            @error('code') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                                        </div>

                                        <div>
                                            <button type="submit" class="w-full inline-flex items-center justify-center px-3 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                                Join
                                            </button>
                                        </div>
                                    </form>

                                    <p class="mt-3 text-xs text-gray-500">If the code is valid the classroom will appear on the left.</p>
                                </div>
                            </aside>
                        @endrole
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>