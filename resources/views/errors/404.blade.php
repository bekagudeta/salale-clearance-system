@extends('layouts.app')

@section('title', 'Page Not Found')

@section('content')
<div class="min-h-screen flex items-center justify-center">
    <div class="bg-white rounded-xl p-8 text-center shadow-lg">
        <h1 class="text-4xl font-bold mb-4 text-gray-800">404</h1>
        <p class="text-gray-600 mb-6">The page you are looking for could not be found.</p>
        <a href="{{ url('/') }}" class="px-4 py-2 bg-purple-600 text-white rounded-lg">Go Home</a>
    </div>
</div>
@endsection
