@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto bg-white dark:bg-slate-800 p-6 rounded-lg shadow">
    <h2 class="text-xl font-bold mb-4">Verify OTP</h2>

    <form method="POST" action="{{ route('password.check-otp') }}">
        @csrf

        <input type="hidden" name="email" value="{{ request('email') }}">

        <label>Enter OTP</label>
        <input type="text" name="otp" required maxlength="6"
               class="w-full px-3 py-2 border rounded-lg dark:bg-slate-700 dark:text-white">

        <button class="w-full mt-4 py-2 bg-emerald-600 text-white rounded-lg">
            Verify OTP
        </button>
    </form>
</div>
@endsection
