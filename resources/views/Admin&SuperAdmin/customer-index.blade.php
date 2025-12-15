<x-app-layout>
    <x-slot name="header">
        <h2 class="dash-title text-xl font-semibold leading-tight">
            {{ __('Customers') }}
        </h2>
    </x-slot>

    <div class="py-12 dashboard-wrap">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="dash-card overflow-hidden">
                <div class="p-6">

                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold">My Customers</h3>

                        {{-- ✅ route name عندك: admin.customer-create --}}
                        <a href="{{ route('admin.customer-create') }}"
                           class="inline-flex items-center px-4 py-2 btn-brand text-xs uppercase tracking-widest">
                            Create Customer
                        </a>
                    </div>

                    @if (!isset($customers) || $customers->count() === 0)
                        <p class="text-white/70">No customers found.</p>
                    @else

                        <div class="dash-table-wrap overflow-x-auto">
                            <table class="dash-table min-w-full">
                                <thead>
                                    <tr>
                                        <th class="text-left px-4 py-3 text-sm font-semibold">#</th>
                                        <th class="text-left px-4 py-3 text-sm font-semibold">Name</th>
                                        <th class="text-left px-4 py-3 text-sm font-semibold">Email</th>
                                        <th class="text-left px-4 py-3 text-sm font-semibold">Phone</th>
                                        <th class="text-left px-4 py-3 text-sm font-semibold">Status</th>
                                        <th class="text-right px-4 py-3 text-sm font-semibold">Actions</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($customers as $customer)
                                        @php
                                            $status = $customer->status?->value ?? (string)($customer->status ?? 'active');
                                        @endphp

                                        <tr>
                                            <td class="px-4 py-3 text-sm">#{{ $customer->id }}</td>
                                            <td class="px-4 py-3 text-sm">{{ $customer->name }}</td>
                                            <td class="px-4 py-3 text-sm">{{ $customer->email }}</td>
                                            <td class="px-4 py-3 text-sm">{{ $customer->phone ?? '—' }}</td>

                                            <td class="px-4 py-3 text-sm">
                                                <span class="px-2 py-1 rounded text-xs font-semibold
                                                    @if($status === 'active') bg-green-600/20 text-green-300 border border-green-600/30
                                                    @else bg-red-600/20 text-red-300 border border-red-600/30
                                                    @endif
                                                ">
                                                    {{ ucfirst($status) }}
                                                </span>
                                            </td>

                                            <td class="px-4 py-3 text-sm text-right">
                                                <div class="inline-flex items-center gap-2">

                                                    {{-- لو عندك show --}}
                                                    @if (Route::has('admin.customer-show'))
                                                        <a href="{{ route('admin.customer-show', $customer) }}"
                                                           class="inline-flex items-center justify-center px-3 py-2 btn-ghost text-xs">
                                                            View
                                                        </a>
                                                    @endif

                                                    <a href="{{ route('admin.customer-edit', $customer) }}"
                                                       class="inline-flex items-center justify-center px-3 py-2 btn-brand text-xs">
                                                        Edit
                                                    </a>

                                                    <form method="POST"
                                                          action="{{ route('admin.customer-destroy', $customer) }}"
                                                          class="inline-flex">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                                onclick="return confirm('Delete this customer?')"
                                                                class="inline-flex items-center justify-center px-3 py-2 btn-danger text-xs">
                                                            Delete
                                                        </button>
                                                    </form>

                                                </div>
                                            </td>
                                        </tr>

                                        {{-- اختياري: لو بترجع orders مع customer --}}
                                        @if(isset($customer->orders) && $customer->orders->count())
                                            <tr>
                                                <td colspan="6" class="px-4 py-3">
                                                    <div class="text-white/70 text-sm mb-2">Orders:</div>
                                                    <div class="dash-table-wrap overflow-x-auto">
                                                        <table class="dash-table min-w-full">
                                                            <thead>
                                                                <tr>
                                                                    <th class="text-left px-3 py-2 text-xs font-semibold">Order ID</th>
                                                                    <th class="text-left px-3 py-2 text-xs font-semibold">Phase</th>
                                                                    <th class="text-left px-3 py-2 text-xs font-semibold">Total</th>
                                                                    <th class="text-left px-3 py-2 text-xs font-semibold">Created</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($customer->orders as $order)
                                                                    @php
                                                                        $phase = $order->current_phase?->value ?? (string)$order->current_phase;
                                                                    @endphp
                                                                    <tr>
                                                                        <td class="px-3 py-2 text-xs">#{{ $order->id }}</td>
                                                                        <td class="px-3 py-2 text-xs">{{ ucfirst($phase) }}</td>
                                                                        <td class="px-3 py-2 text-xs">{{ $order->total_price }}</td>
                                                                        <td class="px-3 py-2 text-xs">{{ optional($order->created_at)->toDateString() }}</td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif

                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- لو $customers paginator --}}
                        @if(method_exists($customers, 'links'))
                            <div class="mt-6">
                                {{ $customers->links() }}
                            </div>
                        @endif

                    @endif

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
