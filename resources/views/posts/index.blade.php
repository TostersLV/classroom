<x-app-layout>


@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Classrooms') }}</h2>
@endsection


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-end items-center mb-6">
                        @role('teacher')
                            <a href="/posts/create" class="inline-flex items-center px-3 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">
                                New Classroom
                            </a>
                        @endrole
                    </div>

                    @if($posts->isEmpty())
                        <div class="border rounded-lg p-6 text-center text-gray-500">
                            {{ __('No classrooms yet. Create one to get started.') }}
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
                                        <div class="w-full h-40 flex items-center justify-center text-gray-700" style="background-color: {{ $color }}; ">
                                            
                                        </div>
                                    @endif

                                    <div class="p-4">
                                        <h3 class="font-semibold text-lg text-gray-900 truncate"> <a href="/posts/{{ $post->id }}">{{ $post->title }}</a></h3>
                                        @if(!empty($post->subject))
                                            <p class="text-sm text-gray-600 mt-1">{{ $post->subject }}</p>
                                        @endif

                                        <div class="mt-3 flex items-center justify-between text-sm text-gray-500">
                                            <span>{{ __('By') }} {{ $post->author }}</span>
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