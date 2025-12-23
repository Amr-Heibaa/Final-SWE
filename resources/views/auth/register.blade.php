<x-guest-layout>

<div class="text-center mb-8">
    <a href="/">
    <h1 class="text-3xl font-extrabold text-white tracking-tight">
        Create account
    </h1>

    </a>

    <p class="mt-2 text-sm text-white/60">
        Sign up to get started
    </p>
</div>


    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="w-full px-3 py-2 rounded bg-black/30 border border-white/10 text-white placeholder-white/40
focus:outline-none focus:ring-2 focus:ring-white/10 focus:border-white/20"
 type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="w-full px-3 py-2 rounded bg-black/30 border border-white/10 text-white placeholder-white/40
focus:outline-none focus:ring-2 focus:ring-white/10 focus:border-white/20"
 type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>


        <!-- Brand Name -->
        <div class="mt-4">
            <x-input-label for="brand_name" :value="__('Brand Name')" />
            <x-text-input
                id="brand_name"
                class="w-full px-3 py-2 rounded bg-black/30 border border-white/10 text-white placeholder-white/40
focus:outline-none focus:ring-2 focus:ring-white/10 focus:border-white/20"

                type="text"
                name="brand_name"
                :value="old('brand_name')"
                required
                autocomplete="organization" />
            <x-input-error :messages="$errors->get('brand_name')" class="mt-2" />
        </div>


        <!-- Phone number -->
        <div class="mt-4">
            <x-input-label for="phone" :value="__('Phone')" class="block text-sm font-medium text-gray-700" />
            <x-text-input id="phone" type="tel" name="phone" :value="old('phone')" required autocomplete="tel" class="w-full px-3 py-2 rounded bg-black/30 border border-white/10 text-white placeholder-white/40
focus:outline-none focus:ring-2 focus:ring-white/10 focus:border-white/20"
 />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="w-full px-3 py-2 rounded bg-black/30 border border-white/10 text-white placeholder-white/40
focus:outline-none focus:ring-2 focus:ring-white/10 focus:border-white/20"

                type="password"
                name="password"
                required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="w-full px-3 py-2 rounded bg-black/30 border border-white/10 text-white placeholder-white/40
focus:outline-none focus:ring-2 focus:ring-white/10 focus:border-white/20"

                type="password"
                name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>


        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="btn-brand px-4 py-2"
>
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>