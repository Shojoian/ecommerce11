@extends('layouts.app')

@section('title', 'Reset Password')

@section('content')

<div class="flex justify-center items-center py-16">

    <div class="w-full max-w-md bg-white dark:bg-slate-800 p-8 rounded-2xl shadow-lg
                border border-slate-200 dark:border-slate-700">

        {{-- Title --}}
        <h2 class="text-2xl font-semibold text-slate-800 dark:text-white mb-6 text-center">
            Reset Password
        </h2>

        {{-- Success / Error messages --}}
        @if ($errors->any())
            <div class="mb-4 p-3 rounded-lg bg-red-100 text-red-700 text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        {{-- Form --}}
        <form method="POST" action="{{ route('password.reset.save') }}" class="space-y-5">
            @csrf

            <input type="hidden" name="email" value="{{ $email }}">
            <input type="hidden" name="token" value="{{ $token }}">

            {{-- New Password --}}
            <div class="flex flex-col">
                <label class="text-sm font-medium text-slate-700 dark:text-slate-200 mb-1">
                    New Password
                </label>
                <input type="password" name="password" required
                    class="px-4 py-2 rounded-lg border border-slate-300 dark:border-slate-600
                           bg-slate-50 dark:bg-slate-700 text-slate-900 dark:text-white
                           focus:ring-2 focus:ring-emerald-500 outline-none transition">
            </div>

            {{-- Confirm Password --}}
            <div class="flex flex-col">
                <label class="text-sm font-medium text-slate-700 dark:text-slate-200 mb-1">
                    Confirm Password
                </label>
                <input type="password" name="password_confirmation" required
                    class="px-4 py-2 rounded-lg border border-slate-300 dark:border-slate-600
                           bg-slate-50 dark:bg-slate-700 text-slate-900 dark:text-white
                           focus:ring-2 focus:ring-emerald-500 outline-none transition">
            </div>

            {{-- Submit Button --}}
            <button type="submit"
                class="w-full py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-medium
                       rounded-lg transition shadow">
                Reset Password
            </button>

            {{-- Back to login --}}
            <p class="text-center text-sm text-slate-600 dark:text-slate-300 mt-2">
                <a href="{{ route('login') }}" class="text-emerald-600 hover:underline">
                    Back to Login
                </a>
            </p>

        </form>

    </div>

</div>

@endsection
