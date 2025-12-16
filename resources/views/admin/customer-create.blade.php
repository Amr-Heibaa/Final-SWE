<x-app-layout>
    <x-slot name="header">
        <h2 class="dash-title text-xl font-semibold leading-tight">
            {{ __('Create Customer') }}
        </h2>
    </x-slot>

    <div class="py-12 dashboard-wrap">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="dash-card overflow-hidden">
                <div class="p-6">

                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold">New Customer</h3>

                        <a href="{{ route('admin.customer-index') }}"
                           class="inline-flex items-center px-4 py-2 btn-ghost text-xs uppercase tracking-widest">
                            Back
                        </a>
                    </div>

                    @if ($errors->any())
                        <div class="mb-4 text-red-300">
                            <ul class="list-disc pl-5">
                                @foreach ($errors->all() as $error)
                                    <li class="text-sm">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.customer-store') }}" class="space-y-4">
                        @csrf

                        <div>
                            <label class="block text-sm mb-1 text-white/80">Name</label>
                            <input type="text" name="name" value="{{ old('name') }}"
                                   class="w-full px-3 py-2 rounded bg-black/30 border border-white/10 text-white" required>
                        </div>

                        <div>
                            <label class="block text-sm mb-1 text-white/80">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                   class="w-full px-3 py-2 rounded bg-black/30 border border-white/10 text-white" required>
                        </div>

                        <div>
                            <label class="block text-sm mb-1 text-white/80">Phone</label>
                            <input type="text" name="phone" value="{{ old('phone') }}"
                                   class="w-full px-3 py-2 rounded bg-black/30 border border-white/10 text-white" required>
                        </div>

                        <div>
                            <label class="block text-sm mb-1 text-white/80">Brand Name</label>
                            <input type="text" name="brand_name" value="{{ old('brand_name') }}"
                                   class="w-full px-3 py-2 rounded bg-black/30 border border-white/10 text-white" required>
                        </div>

                        <div>
                            <label class="block text-sm mb-1 text-white/80">Password</label>
                            <input type="password" name="password"
                                   class="w-full px-3 py-2 rounded bg-black/30 border border-white/10 text-white" required>
                        </div>

                        <div>
                            <label class="block text-sm mb-1 text-white/80">Confirm Password</label>
                            <input type="password" name="password_confirmation"
                                   class="w-full px-3 py-2 rounded bg-black/30 border border-white/10 text-white" required>
                        </div>

                        <div class="pt-2 flex items-center gap-2">
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 btn-brand text-xs uppercase tracking-widest">
                                Create
                            </button>

                            <a href="{{ route('admin.customer-index') }}"
                               class="inline-flex items-center px-4 py-2 btn-ghost text-xs uppercase tracking-widest">
                                Cancel
                            </a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
