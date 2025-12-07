@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow">

    <h2 class="text-xl font-semibold mb-4">My Profile</h2>

    <form action="{{ route('profile.update') }}" method="POST">
        @csrf
        @method('PUT')

        <label class="block mb-2 text-sm font-medium">Name</label>
        <input type="text" name="name" value="{{ $user->name }}" class="w-full border rounded px-3 py-2 mb-3">

        <label class="block mb-2 text-sm font-medium">Email</label>
        <input type="email" name="email" value="{{ $user->email }}" class="w-full border rounded px-3 py-2 mb-3">

        <button class="px-4 py-2 bg-emerald-600 text-white rounded">
            Save Changes
        </button>
    </form>

</div>
@endsection
