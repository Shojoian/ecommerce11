<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information, avatar, and email address.") }}
        </p>
    </header>

    {{-- Send verification email --}}
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post"
          action="{{ route('profile.update') }}"
          class="mt-6 space-y-6"
          enctype="multipart/form-data">

        @csrf
        @method('patch')

        {{-- Avatar Upload --}}
        <div>
            <x-input-label for="avatar" value="Avatar" />

            <div class="flex items-center gap-4 mt-2">

                {{-- Preview --}}
                <img id="avatarPreview"
                     src="{{ $user->avatar
                            ? asset('storage/' . $user->avatar)
                            : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) }}"
                     class="w-16 h-16 rounded-full object-cover border shadow">

                {{-- File input --}}
                <input id="avatar"
                       name="avatar"
                       type="file"
                       accept="image/*"
                       class="text-sm block w-40 cursor-pointer border border-gray-300 rounded-md p-2" />

            </div>

            @error('avatar')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <script>
            document.getElementById('avatar').addEventListener('change', function () {
                const file = this.files[0];
                const preview = document.getElementById('avatarPreview');

                if (file) {
                    preview.src = URL.createObjectURL(file);
                }
            });
        </script>

        {{-- Name --}}
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text"
                          class="mt-1 block w-full"
                          :value="old('name', $user->name)"
                          required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        {{-- Email --}}
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email"
                          class="mt-1 block w-full"
                          :value="old('email', $user->email)"
                          required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            {{-- Email Verification Reminder --}}
            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2 text-sm text-gray-800">
                    {{ __('Your email address is unverified.') }}

                    <button form="send-verification"
                            class="underline text-gray-600 hover:text-gray-900">
                        {{ __('Click here to re-send the verification email.') }}
                    </button>
                </div>

                @if (session('status') === 'verification-link-sent')
                    <p class="mt-2 font-medium text-sm text-green-600">
                        {{ __('A new verification link has been sent to your email address.') }}
                    </p>
                @endif
            @endif
        </div>

        {{-- Save Button --}}
        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }"
                   x-show="show"
                   x-transition
                   x-init="setTimeout(() => show = false, 2000)"
                   class="text-sm text-gray-600">
                    {{ __('Saved.') }}
                </p>
            @endif
        </div>
    </form>
</section>
