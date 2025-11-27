@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
    @include('profile.partials.update-profile-information-form')
    @if (view()->exists('profile.partials.update-password-form'))
        @include('profile.partials.update-password-form')
    @endif
    @if (view()->exists('profile.partials.delete-user-form'))
        @include('profile.partials.delete-user-form')
    @endif
</div>
@endsection
