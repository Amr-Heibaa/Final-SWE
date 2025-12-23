<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center px-4 py-10 bg-[#0b1220]">
        <div class="w-full max-w-md">


            {{-- Header --}}
            <div class="text-center mb-8">
                <a href="/">
                    <h1 class="text-2xl font-semibold text-white">Welcome back</h1>
                </a>
                <p class="text-white/60 text-sm mt-1">Sign in to continue</p>
            </div>



            {{-- Card --}}
            <div class="rounded-2xl border border-white/10 bg-white/5 backdrop-blur p-6 shadow-xl">
                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                @if ($errors->any())
                <div class="mb-4 px-4 py-3 rounded bg-red-600/20 text-red-200 border border-red-600/30">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                        <li class="text-sm">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm mb-1 text-white/80">Email</label>
                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            autocomplete="username"
                            class="w-full px-3 py-2 rounded bg-black/30 border border-white/10 text-white placeholder:text-white/30 focus:outline-none focus:ring-2 focus:ring-white/10"
                            placeholder="you@example.com" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="password" class="block text-sm mb-1 text-white/80">Password</label>
                        <input
                            id="password"
                            type="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            class="w-full px-3 py-2 rounded bg-black/30 border border-white/10 text-white placeholder:text-white/30 focus:outline-none focus:ring-2 focus:ring-white/10"
                            placeholder="••••••••" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    {{-- Remember + Forgot --}}
                    <div class="flex items-center justify-between">
                        <label for="remember_me" class="inline-flex items-center gap-2">
                            <input
                                id="remember_me"
                                type="checkbox"
                                name="remember"
                                class="rounded border-white/20 bg-black/30 text-white focus:ring-white/20">
                            <span class="text-sm text-white/70">Remember me</span>
                        </label>

                        @if (Route::has('password.request'))
                        <a
                            class="text-sm text-white/70 hover:text-white underline underline-offset-4"
                            href="{{ route('password.request') }}">
                            Forgot password?
                        </a>
                        @endif
                    </div>

                    {{-- Actions --}}
                    <div class="pt-2">
                        <button
                            type="submit"
                            class="w-full inline-flex justify-center items-center px-4 py-2 btn-brand text-xs uppercase tracking-widest">
                            Log in
                        </button>
                    </div>
                </form>
            </div>

            {{-- Footer --}}
            <p class="text-center text-xs text-white/40 mt-6">
                © {{ date('Y') }} — All rights reserved
            </p>
        </div>
    </div>
</x-guest-layout>