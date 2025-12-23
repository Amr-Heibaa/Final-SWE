<x-app-layout>
    <x-slot name="header">
        <h2 class="dash-title text-xl font-semibold leading-tight">
            {{ __('Edit Admin') }}
        </h2>
    </x-slot>

    <div class="py-12 dashboard-wrap">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="dash-card overflow-hidden">
                <div class="p-6">

                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-semibold text-white">Edit Admin</h3>
                            <div class="text-white/60 text-sm">
                                {{ $admin->name }} — {{ $admin->email }}
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.show', $admin) }}"
                               class="inline-flex items-center px-4 py-2 btn-ghost text-xs uppercase tracking-widest">
                                Back
                            </a>

                            <a href="{{ route('admin.index') }}"
                               class="inline-flex items-center px-4 py-2 btn-ghost text-xs uppercase tracking-widest">
                                Admins
                            </a>
                        </div>
                    </div>

                    
                    @if(session('success'))
                        <div class="mb-4 px-4 py-3 rounded bg-green-600/20 text-green-200 border border-green-600/30">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-4 px-4 py-3 rounded bg-red-600/20 text-red-200 border border-red-600/30">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-4 px-4 py-3 rounded bg-red-600/20 text-red-200 border border-red-600/30">
                            <ul class="list-disc pl-5">
                                @foreach ($errors->all() as $error)
                                    <li class="text-sm">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.update', $admin) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-12 gap-4">

                            {{-- Name --}}
                            <div class="col-span-12 md:col-span-6">
                                <label class="block text-sm mb-1 text-white/80">Name</label>
                                <input type="text"
                                       name="name"
                                       value="{{ old('name', $admin->name) }}"
                                       class="w-full px-3 py-2 rounded bg-black/30 border border-white/10 text-white"
                                       required>
                            </div>

                            {{-- Email --}}
                            <div class="col-span-12 md:col-span-6">
                                <label class="block text-sm mb-1 text-white/80">Email</label>
                                <input type="email"
                                       name="email"
                                       value="{{ old('email', $admin->email) }}"
                                       class="w-full px-3 py-2 rounded bg-black/30 border border-white/10 text-white"
                                       required>
                            </div>

                            {{-- Phone --}}
                            <div class="col-span-12 md:col-span-6">
                                <label class="block text-sm mb-1 text-white/80">Phone (optional)</label>
                                <input type="text"
                                       name="phone"
                                       value="{{ old('phone', $admin->phone ?? '') }}"
                                       class="w-full px-3 py-2 rounded bg-black/30 border border-white/10 text-white"
                                       placeholder="e.g. 010...">
                                <p class="text-xs text-white/50 mt-1">Leave empty if you don't use phone for admins.</p>
                            </div>

                            {{-- Password --}}
                            <div class="col-span-12 md:col-span-6">
                                <label class="block text-sm mb-1 text-white/80">New Password (optional)</label>
                                <input type="password"
                                       name="password"
                                       class="w-full px-3 py-2 rounded bg-black/30 border border-white/10 text-white"
                                       placeholder="••••••••">
                                <p class="text-xs text-white/50 mt-1">Leave empty to keep current password.</p>
                            </div>

                            {{-- Confirm Password --}}
                            <div class="col-span-12 md:col-span-6">
                                <label class="block text-sm mb-1 text-white/80">Confirm New Password</label>
                                <input type="password"
                                       name="password_confirmation"
                                       class="w-full px-3 py-2 rounded bg-black/30 border border-white/10 text-white"
                                       placeholder="••••••••">
                            </div>

                            {{-- Role --}}
                            <div class="col-span-12 md:col-span-6">
                                <label class="block text-sm mb-1 text-white/80">Role</label>
                                <input type="text"
                                       value="{{ is_object($admin->role) ? ($admin->role->value ?? 'admin') : (string)$admin->role }}"
                                       class="w-full px-3 py-2 rounded bg-black/20 border border-white/10 text-white/70"
                                       disabled>
                                <p class="text-xs text-white/50 mt-1">Role is fixed to Admin in this screen.</p>
                            </div>

                        </div>

                        <hr class="border-white/10">

                        <div class="flex items-center gap-2">
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 btn-brand text-xs uppercase tracking-widest">
                                Save Changes
                            </button>

                            <a href="{{ route('admin.show', $admin) }}"
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
