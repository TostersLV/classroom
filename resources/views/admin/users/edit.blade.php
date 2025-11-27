@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow p-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Edit Roles</h1>
            <p class="text-gray-600 mb-8">{{ $user->first_name }} {{ $user->last_name }}</p>

            <form action="{{ route('admin.users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-8">
                    <label class="block text-sm font-semibold text-gray-900 mb-4">Assign Role</label>
                    <div class="space-y-3 bg-gray-50 p-4 rounded-lg">
                        @foreach($roles as $role)
                            <div class="flex items-center">
                                <input
                                    type="radio"
                                    name="role"
                                    value="{{ $role }}"
                                    id="role_{{ $role }}"
                                    {{ $user->hasRole($role) ? 'checked' : '' }}
                                    class="h-5 w-5 text-indigo-600 focus:ring-indigo-500 border-gray-300 cursor-pointer"
                                >
                                <label for="role_{{ $role }}" class="ml-3 block text-sm font-medium text-gray-700 capitalize cursor-pointer">
                                    {{ $role }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="flex gap-4">
                    <button type="submit" class="px-6 py-2 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition">
                        Save Changes
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="px-6 py-2 bg-gray-200 text-gray-800 font-medium rounded-lg hover:bg-gray-300 transition">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection