<x-app-layout>
    <x-slot name="header">
        <h2 class="dash-title text-xl font-semibold leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    @php
    $role = auth()->user()->role?->value ?? auth()->user()->role; 
    $isCustomer = ($role === 'customer');
    $isAdmin = ($role === 'admin');
    $isSuperAdmin = ($role === 'superAdmin' || $role === 'superadmin' || $role === 'super_admin');
    @endphp

    <div class="py-12 dashboard-wrap">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- =========================
                 ADMIN / SUPER ADMIN PANEL
            ========================== --}}
            @if($isAdmin || $isSuperAdmin)
            <div class="dash-card overflow-hidden mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Management</h3>

                    <div class="flex flex-wrap gap-3">
                        {{-- Customers Management (Admin + SuperAdmin) --}}
                        <a href="{{ route('admin.customer-index') }}"
                            class="inline-flex items-center px-4 py-2 btn-brand text-xs uppercase tracking-widest">
                            Manage Customers
                        </a>

                        <a href="{{ route('admin.customer-create') }}"
                            class="inline-flex items-center px-4 py-2 btn-ghost text-xs uppercase tracking-widest">
                            Create Customer
                        </a>

                        {{-- Orders Management (Admin + SuperAdmin) --}}
                        <a href="{{ route('admin.orders.index') }}"
                            class="inline-flex items-center px-4 py-2 btn-brand text-xs uppercase tracking-widest">
                            Manage Orders
                        </a>

                        <a href="{{ route('admin.orders.create') }}"
                            class="inline-flex items-center px-4 py-2 btn-ghost text-xs uppercase tracking-widest">
                            Create Order
                        </a>

                        {{-- Admins Management (SuperAdmin only) --}}
                        @if($isSuperAdmin)
                        <a href="{{ route('admin.index') }}"
                            class="inline-flex items-center px-4 py-2 btn-brand text-xs uppercase tracking-widest">
                            Manage Admins
                        </a>

                        <a href="{{ route('admin.create') }}"
                            class="inline-flex items-center px-4 py-2 btn-ghost text-xs uppercase tracking-widest">
                            Create Admin
                        </a>
                        @endif
                    </div>

                    <p class="text-white/60 mt-4 text-sm">
                    </p>
                </div>
            </div>
            @endif


            {{-- =========================
                 CUSTOMER DASHBOARD
            ========================== --}}
            @if($isCustomer)
            <div class="dash-card overflow-hidden">
                <div class="p-6">

                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold">My Meeting Requests</h3>

                        <a href="{{ route('meetings.create') }}"
                            class="inline-flex items-center px-4 py-2 btn-brand text-xs uppercase tracking-widest">
                            Create Meeting
                        </a>
                    </div>

                    @if (!isset($meetings) || $meetings->count() === 0)
                    <p class="text-white/70">No meetings found for your account.</p>
                    @else
                    <div class="dash-table-wrap overflow-x-auto">
                        <table class="dash-table min-w-full">
                            <thead>
                                <tr>
                                    <th class="text-left px-4 py-3 text-sm font-semibold">#</th>
                                    <th class="text-left px-4 py-3 text-sm font-semibold">Name</th>
                                    <th class="text-left px-4 py-3 text-sm font-semibold">Phone</th>
                                    <th class="text-left px-4 py-3 text-sm font-semibold">Brand</th>
                                    <th class="text-left px-4 py-3 text-sm font-semibold">Scheduled</th>
                                    <th class="text-left px-4 py-3 text-sm font-semibold">Status</th>
                                    <th class="text-right px-4 py-3 text-sm font-semibold">Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($meetings as $meeting)
                                @php $status = $meeting->status?->value ?? 'pending'; @endphp

                                <tr>
                                    <td class="px-4 py-3 text-sm">#{{ $meeting->id }}</td>
                                    <td class="px-4 py-3 text-sm">{{ $meeting->name }}</td>
                                    <td class="px-4 py-3 text-sm">{{ $meeting->phone }}</td>
                                    <td class="px-4 py-3 text-sm">{{ $meeting->brand_name }}</td>
                                    <td class="px-4 py-3 text-sm">
                                        {{ optional($meeting->scheduled_date)->format('Y-m-d H:i') }}
                                    </td>

                                    <td class="px-4 py-3 text-sm">
                                        <span class="px-2 py-1 rounded text-xs font-semibold
                                                    @if($status === 'pending') bg-yellow-500/20 text-yellow-300 border border-yellow-500/30
                                                    @elseif($status === 'completed') bg-green-600/20 text-green-300 border border-green-600/30
                                                    @else bg-red-600/20 text-red-300 border border-red-600/30
                                                    @endif">
                                            {{ ucfirst($status) }}
                                        </span>
                                    </td>

                                    <td class="px-4 py-3 text-sm text-right">
                                        <div class="inline-flex items-center gap-2">
                                            <a href="{{ route('meetings.show', $meeting) }}"
                                                class="inline-flex items-center justify-center px-3 py-2 btn-ghost text-xs">
                                                View
                                            </a>

                                            <a href="{{ route('meetings.edit', $meeting) }}"
                                                class="inline-flex items-center justify-center px-3 py-2 btn-brand text-xs">
                                                Edit
                                            </a>

                                            <form method="POST" action="{{ route('meetings.destroy', $meeting) }}" class="inline-flex">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    onclick="return confirm('Delete this meeting?')"
                                                    class="inline-flex items-center justify-center px-3 py-2 btn-danger text-xs">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        {{ $meetings->links() }}
                    </div>
                    @endif

                </div>
            </div>
            @endif

        </div>
    </div>
</x-app-layout>