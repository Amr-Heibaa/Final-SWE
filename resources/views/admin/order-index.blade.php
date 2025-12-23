<x-app-layout>
    <x-slot name="header">
        <h2 class="dash-title text-xl font-semibold leading-tight">
            {{ __('Orders') }}
        </h2>
    </x-slot>

    <div class="py-12 dashboard-wrap">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="dash-card overflow-hidden">
                <div class="p-6">

                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold">Orders</h3>

                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.orders.create') }}"
                                class="inline-flex items-center px-4 py-2 btn-brand text-xs uppercase tracking-widest">
                                Create Order
                            </a>

                            <a href="{{ route('dashboard') }}"
                                class="inline-flex items-center px-4 py-2 btn-ghost text-xs uppercase tracking-widest">
                                Back
                            </a>
                        </div>
                    </div>

                    {{-- Flash messages --}}
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

                    {{-- Filters --}}
                    <form method="GET" action="{{ route('admin.orders.index') }}"
                        class="grid grid-cols-12 gap-3 mb-6">

                        <div class="col-span-12 md:col-span-4">
                            <label class="block text-sm mb-1 text-white/80">Search Customer</label>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Name or email..."
                                class="w-full px-3 py-2 rounded bg-black/30 border border-white/10 text-white">
                        </div>

                        <div class="col-span-12 md:col-span-3">
                            <label class="block text-sm mb-1 text-white/80">Customer</label>
                            <select name="customer_id"
                                class="w-full px-3 py-2 rounded bg-black/30 border border-white/10 text-white">
                                <option value="">All customers</option>
                                @foreach($customers as $c)
                                <option value="{{ $c->id }}" @selected(request('customer_id')==$c->id)>
                                    {{ $c->name }} ({{ $c->email }})
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-span-12 md:col-span-3">
                            <label class="block text-sm mb-1 text-white/80">Phase</label>
                            <select name="phase"
                                class="w-full px-3 py-2 rounded bg-black/30 border border-white/10 text-white">
                                <option value="">All phases</option>
                                @foreach($phases as $value => $label)
                                <option value="{{ $value }}" @selected(request('phase')==$value)>
                                    {{ $label }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-span-12 md:col-span-2">
                            <label class="block text-sm mb-1 text-white/80">Printing</label>
                            <select name="requires_printing"
                                class="w-full px-3 py-2 rounded bg-black/30 border border-white/10 text-white">
                                <option value="" @selected(request()->has('requires_printing') === false)>All</option>
                                <option value="1" @selected(request('requires_printing')==='1' )>Yes</option>
                                <option value="0" @selected(request('requires_printing')==='0' )>No</option>
                            </select>
                        </div>

                        <div class="col-span-12 flex items-center gap-2 pt-1">
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 btn-brand text-xs uppercase tracking-widest">
                                Apply
                            </button>

                            <a href="{{ route('admin.orders.index') }}"
                                class="inline-flex items-center px-4 py-2 btn-ghost text-xs uppercase tracking-widest">
                                Reset
                            </a>
                        </div>
                    </form>

                    {{-- Table --}}
                    @if($orders->count() === 0)
                    <p class="text-white/70">No orders found.</p>
                    @else
                    <div class="dash-table-wrap overflow-x-auto">
                        <table class="dash-table min-w-full">
                            <thead>
                                <tr>
                                    <th class="text-left px-4 py-3 text-sm font-semibold">#</th>
                                    <th class="text-left px-4 py-3 text-sm font-semibold">Customer</th>
                                    <th class="text-left px-4 py-3 text-sm font-semibold">Meeting</th>
                                    <th class="text-left px-4 py-3 text-sm font-semibold">Phase</th>
                                    <th class="text-left px-4 py-3 text-sm font-semibold">Printing</th>
                                    <th class="text-left px-4 py-3 text-sm font-semibold">Total</th>
                                    <th class="text-left px-4 py-3 text-sm font-semibold">Created</th>
                                    <th class="text-right px-4 py-3 text-sm font-semibold">Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($orders as $order)
                                @php
                                $phase = $order->current_phase?->value ?? $order->current_phase;
                                $isPrinting = (bool)($order->requires_printing ?? false);

                                $phaseLabel = $phases[(string)$phase] ?? ucfirst((string)$phase);

                                $backUrl = url()->current() . (request()->getQueryString() ? ('?' . request()->getQueryString()) : '');
                                @endphp

                                <tr>
                                    <td class="px-4 py-3 text-sm">#{{ $order->id }}</td>

                                    <td class="px-4 py-3 text-sm">
                                        <div class="font-semibold text-white">
                                            {{ $order->customer?->name ?? $order->customer_name ?? '—' }}
                                        </div>
                                        <div class="text-white/60 text-xs">
                                            {{ $order->customer?->email ?? '—' }}
                                        </div>
                                    </td>

                                    <td class="px-4 py-3 text-sm">
                                        @if($order->meeting)
                                        <div class="text-white">
                                            #{{ $order->meeting->id }} - {{ $order->meeting->name }}
                                        </div>
                                        <div class="text-white/60 text-xs">
                                            {{ optional($order->meeting->scheduled_date)->format('Y-m-d H:i') }}
                                        </div>
                                        @else
                                        <span class="text-white/60">—</span>
                                        @endif
                                    </td>

                                    <td class="px-4 py-3 text-sm">
                                        <div class="flex items-center gap-2">
                                            <span class="px-2 py-1 rounded text-xs font-semibold bg-white/10 border border-white/10 text-white whitespace-nowrap">
                                                {{ $phaseLabel }}
                                            </span>

                                            <form method="POST" action="{{ route('admin.orders.update', $order->id) }}"
                                                class="inline-flex items-center gap-2">
                                                @csrf
                                                @method('PATCH')

                                                <input type="hidden" name="redirect_to" value="{{ $backUrl }}">

                                                <select name="current_phase"
                                                    class="px-2 py-1 rounded bg-black/30 border border-white/10 text-white text-xs">
                                                    @foreach($phases as $value => $label)
                                                    <option value="{{ $value }}" @selected((string)$phase===(string)$value)>
                                                        {{ $label }}
                                                    </option>
                                                    @endforeach
                                                </select>

                                                <button type="submit"
                                                    class="inline-flex items-center justify-center px-3 py-2 btn-brand text-xs">
                                                    Update
                                                </button>
                                            </form>
                                        </div>
                                    </td>

                                    <td class="px-4 py-3 text-sm">
                                        @if($isPrinting)
                                        <span class="px-2 py-1 rounded text-xs font-semibold bg-yellow-500/20 text-yellow-300 border border-yellow-500/30">
                                            Yes
                                        </span>
                                        @else
                                        <span class="px-2 py-1 rounded text-xs font-semibold bg-white/10 border border-white/10 text-white/80">
                                            No
                                        </span>
                                        @endif
                                    </td>

                                    <td class="px-4 py-3 text-sm">
                                        {{ number_format((float)($order->total_price ?? 0), 2) }}
                                    </td>

                                    <td class="px-4 py-3 text-sm text-white/70">
                                        {{ optional($order->created_at)->format('Y-m-d H:i') }}
                                    </td>

                                    <td class="px-4 py-3 text-sm text-right">
                                        <div class="inline-flex items-center gap-2">

                                            <a href="{{ route('admin.orders.show', $order->id) }}"
                                                class="inline-flex items-center justify-center px-3 py-2 btn-ghost text-xs">
                                                View
                                            </a>

                                            <a href="{{ route('admin.orders.edit', $order->id) }}"
                                                class="inline-flex items-center justify-center px-3 py-2 btn-brand text-xs">
                                                Edit
                                            </a>

                                            <form method="POST" action="{{ route('admin.orders.destroy', $order->id) }}"
                                                class="inline-flex">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    onclick="return confirm('Delete this order?')"
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

                    {{-- Pagination--}}
                    <div class="mt-6">
                        {{ $orders->appends(request()->query())->links() }}
                    </div>
                    @endif

                </div>
            </div>

        </div>
    </div>
</x-app-layout>