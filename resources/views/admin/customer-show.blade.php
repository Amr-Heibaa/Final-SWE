<x-app-layout>
    <x-slot name="header">
        <h2 class="dash-title text-xl font-semibold leading-tight">
            {{ __('Customer Details') }}
        </h2>
    </x-slot>

    <div class="py-12 dashboard-wrap">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            <div class="dash-card overflow-hidden">
                <div class="p-6">

                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-semibold">{{ $customer->name }}</h3>
                            <p class="text-white/60 text-sm">Customer Profile</p>
                        </div>

                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.customer-index') }}"
                               class="inline-flex items-center px-4 py-2 btn-ghost text-xs uppercase tracking-widest">
                                Back
                            </a>

                            <a href="{{ route('admin.customer-edit', $customer->id) }}"
                               class="inline-flex items-center px-4 py-2 btn-brand text-xs uppercase tracking-widest">
                                Edit
                            </a>

                            <form method="POST" action="{{ route('admin.customer-destroy', $customer->id) }}" class="inline-flex">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        onclick="return confirm('Delete this customer?')"
                                        class="inline-flex items-center px-4 py-2 btn-danger text-xs uppercase tracking-widest">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="mb-4 px-4 py-3 rounded bg-green-600/15 border border-green-600/30 text-green-200 text-sm">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-4 px-4 py-3 rounded bg-red-600/15 border border-red-600/30 text-red-200 text-sm">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <div class="rounded border border-white/10 bg-black/20 p-4">
                            <p class="text-white/60 text-xs uppercase tracking-widest mb-1">Email</p>
                            <p class="text-white text-sm">{{ $customer->email }}</p>
                        </div>

                        <div class="rounded border border-white/10 bg-black/20 p-4">
                            <p class="text-white/60 text-xs uppercase tracking-widest mb-1">Phone</p>
                            <p class="text-white text-sm">{{ $customer->phone ?? '—' }}</p>
                        </div>

                        <div class="rounded border border-white/10 bg-black/20 p-4">
                            <p class="text-white/60 text-xs uppercase tracking-widest mb-1">Brand Name</p>
                            <p class="text-white text-sm">{{ $customer->brand_name ?? '—' }}</p>
                        </div>

                        <div class="rounded border border-white/10 bg-black/20 p-4">
                            <p class="text-white/60 text-xs uppercase tracking-widest mb-1">Role</p>
                            <p class="text-white text-sm">
                                {{ $customer->role?->value ?? $customer->role }}
                            </p>
                        </div>

                        <div class="rounded border border-white/10 bg-black/20 p-4">
                            <p class="text-white/60 text-xs uppercase tracking-widest mb-1">Created At</p>
                            <p class="text-white text-sm">
                                {{ optional($customer->created_at)->format('Y-m-d H:i') ?? '—' }}
                            </p>
                        </div>

                        <div class="rounded border border-white/10 bg-black/20 p-4">
                            <p class="text-white/60 text-xs uppercase tracking-widest mb-1">Assigned Admin</p>
                            <p class="text-white text-sm">
                                
                                {{ optional($customer->admin)->name ?? ($customer->admin_id ?? '—') }}
                            </p>
                        </div>

                    </div>

                    <div class="mt-6 flex flex-wrap gap-2">
       
                        
                        <a href="{{ route('admin.meetings.index', ['customer_id' => $customer->id]) }}"
                           class="inline-flex items-center px-4 py-2 btn-ghost text-xs uppercase tracking-widest">
                            View Meetings
                        </a> 
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
