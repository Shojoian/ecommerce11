@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

    {{-- LEFT SIDEBAR --}}
    <aside class="lg:col-span-1 space-y-4">

        {{-- User Info Card --}}
        <div class="bg-white border rounded-2xl shadow-sm p-4">
            <div class="flex items-center gap-3">

                {{-- Avatar --}}
                <div class="w-12 h-12 rounded-full bg-slate-200 flex items-center justify-center overflow-hidden border">
                    @if(auth()->user()->avatar)
                        <img src="{{ asset('storage/' . auth()->user()->avatar) }}"
                             alt="Avatar" class="w-full h-full object-cover">
                    @else
                        <span class="text-sm font-semibold text-slate-700">
                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                        </span>
                    @endif
                </div>

                <div>
                    <p class="text-sm font-medium text-slate-900">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-slate-500">{{ auth()->user()->email }}</p>
                </div>
            </div>
        </div>

        {{-- Sidebar Navigation --}}
        <nav class="bg-white border rounded-2xl shadow-sm p-3 text-sm">

            <a href="{{ route('profile.edit') }}"
               class="flex items-center justify-between px-3 py-2 rounded-lg bg-slate-900 text-white mb-1">
                <span>Profile</span>
                <span class="text-[10px] uppercase tracking-wide">Current</span>
            </a>

            <a href="{{ route('orders.index') }}"
               class="block px-3 py-2 rounded-lg hover:bg-slate-100 text-slate-700 mb-1">
                My Orders
            </a>

            @role('admin')
                <a href="{{ route('admin.dashboard') }}"
                   class="block px-3 py-2 rounded-lg hover:bg-slate-100 text-slate-700 mb-1">
                    Admin Panel
                </a>
            @endrole

        </nav>

    </aside>

    {{-- MAIN CONTENT --}}
    <section class="lg:col-span-3 space-y-6">

        {{-- MAIN PROFILE CARD --}}
        <div class="bg-white border rounded-2xl shadow-sm p-8 max-w-3xl mx-auto">

            <h2 class="text-xl font-semibold mb-1">Profile Settings</h2>
            <p class="text-xs text-slate-500 mb-6">
                Update your avatar, name, email, and password.
            </p>

            {{-- PROFILE FORM --}}
            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PATCH')

                {{-- Avatar + Upload --}}
                <div class="flex items-center gap-6">

                    <div class="w-20 h-20 rounded-full overflow-hidden border bg-slate-200 shadow flex items-center justify-center">
                        @if(auth()->user()->avatar)
                            <img src="{{ asset('storage/' . auth()->user()->avatar) }}"
                                 class="w-full h-full object-cover">
                        @else
                            <span class="text-lg font-semibold text-slate-700">
                                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                            </span>
                        @endif
                    </div>

                    <div>
                        <label class="text-xs font-medium text-slate-600">Avatar</label>
                        <input type="file" name="avatar" class="mt-1 text-sm">
                    </div>
                </div>

                {{-- Name --}}
                <div>
                    <label class="text-xs font-medium text-slate-600">Name</label>
                    <input type="text" name="name"
                           value="{{ auth()->user()->name }}"
                           class="border rounded-lg px-3 py-2 w-full mt-1 focus:ring focus:ring-emerald-300/40">
                </div>

                {{-- Email --}}
                <div>
                    <label class="text-xs font-medium text-slate-600">Email</label>
                    <input type="email" name="email"
                           value="{{ auth()->user()->email }}"
                           class="border rounded-lg px-3 py-2 w-full mt-1 focus:ring focus:ring-emerald-300/40">
                </div>

                {{-- Save --}}
                <button class="bg-slate-900 text-white px-5 py-2 rounded-lg hover:bg-black">
                    Save
                </button>

            </form>

            {{-- PASSWORD SECTION --}}
            <div class="mt-10 border-t pt-8">

                <h3 class="text-lg font-semibold mb-2">Change Password</h3>
                <p class="text-xs text-slate-500 mb-4">
                    Ensure your account stays secure.
                </p>

                <div class="max-w-md">
                    @include('profile.partials.update-password-form')
                </div>

            </div>
        </div>

        {{-- DANGER ZONE --}}
        <div class="bg-white border rounded-2xl shadow-sm p-6 max-w-3xl mx-auto mt-8">
            <h2 class="text-lg font-semibold mb-1 text-red-600">Danger Zone</h2>
            <p class="text-xs text-slate-500 mb-4">
                Permanently delete your account and all associated data.
            </p>

            @include('profile.partials.delete-user-form')
        </div>

    </section>

</div>
@endsection
