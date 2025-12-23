<x-app-layout>
    <x-slot name="header">
        <h2 class="dash-title text-xl font-semibold leading-tight">
            {{ __('Order') }}
        </h2>
    </x-slot>

    <div class="py-12 dashboard-wrap">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="dash-card overflow-hidden">
                <div class="p-6">

                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-semibold text-white">Order</h3>
                            <div class="text-white/60 text-sm">#{{ $order->id }}</div>
                        </div>

                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.orders.index') }}"
                                class="inline-flex items-center px-4 py-2 btn-ghost text-xs uppercase tracking-widest">
                                Back
                            </a>

                            <a href="{{ route('admin.orders.edit', $order->id) }}"
                                class="inline-flex items-center px-4 py-2 btn-brand text-xs uppercase tracking-widest">
                                Edit
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

                    @php
                    $phaseVal = $order->current_phase instanceof \App\Enums\OrderPhaseEnum
                    ? $order->current_phase->value
                    : (string) $order->current_phase;

                    $phases = \App\Enums\OrderPhaseEnum::forDropdown((bool) $order->requires_printing);
                    $phaseLabel = $phases[$phaseVal] ?? ucfirst($phaseVal);
                    @endphp

                    <div class="grid grid-cols-12 gap-4 mb-8">
                        <div class="col-span-12 md:col-span-6 p-4 rounded border border-white/10 bg-black/20">
                            <div class="text-white/70 text-sm mb-2">Customer</div>

                            <div class="text-white font-semibold">
                                {{ $order->customer?->name ?? $order->customer_name ?? '—' }}
                            </div>

                            <div class="text-white/60 text-sm">
                                {{ $order->customer?->email ?? '—' }}
                            </div>

                            <div class="text-white/60 text-sm mt-1">
                                Brand: <span class="text-white">{{ $order->brand_name ?? '—' }}</span>
                            </div>
                        </div>

                        <div class="col-span-12 md:col-span-6 p-4 rounded border border-white/10 bg-black/20">
                            <div class="grid grid-cols-12 gap-3">
                                <div class="col-span-6">
                                    <div class="text-white/70 text-sm mb-1">Phase</div>
                                    <span class="px-2 py-1 rounded text-xs font-semibold bg-white/10 border border-white/10 text-white">
                                        {{ $phaseLabel }}
                                    </span>

                                    <form method="POST" action="{{ route('admin.orders.update', $order->id) }}" class="mt-3 flex items-center gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="redirect_to" value="{{ url()->current() }}">

                                        <select name="current_phase"
                                            class="px-2 py-1 rounded bg-black/30 border border-white/10 text-white text-xs">
                                            @foreach($phases as $value => $label)
                                            <option value="{{ $value }}" @selected((string)$phaseVal===(string)$value)>
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

                                <div class="col-span-6">
                                    <div class="text-white/70 text-sm mb-1">Printing</div>
                                    @if($order->requires_printing)
                                    <span class="px-2 py-1 rounded text-xs font-semibold bg-yellow-500/20 text-yellow-300 border border-yellow-500/30">
                                        Yes
                                    </span>
                                    @else
                                    <span class="px-2 py-1 rounded text-xs font-semibold bg-white/10 border border-white/10 text-white/80">
                                        No
                                    </span>
                                    @endif
                                </div>

                                <div class="col-span-6 mt-3">
                                    <div class="text-white/70 text-sm mb-1">Total</div>
                                    <div class="text-white font-semibold">
                                        {{ number_format((float)($order->total_price ?? 0), 2) }}
                                    </div>
                                </div>

                                <div class="col-span-6 mt-3">
                                    <div class="text-white/70 text-sm mb-1">Created</div>
                                    <div class="text-white/80 text-sm">
                                        {{ optional($order->created_at)->format('Y-m-d H:i') }}
                                    </div>
                                </div>

                                <div class="col-span-12 mt-3">
                                    <div class="text-white/70 text-sm mb-1">Meeting</div>
                                    @if($order->meeting)
                                    <div class="text-white text-sm">
                                        #{{ $order->meeting->id }} - {{ $order->meeting->name }}
                                    </div>
                                    <div class="text-white/60 text-xs">
                                        {{ optional($order->meeting->scheduled_date)->format('Y-m-d H:i') }}
                                    </div>
                                    @else
                                    <div class="text-white/60 text-sm">—</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <h4 class="text-white font-semibold mb-3">Items</h4>

                    @if($order->items->count() === 0)
                    <p class="text-white/70">No items found.</p>
                    @else
                    <div class="space-y-4">
                        @foreach($order->items as $item)
                        @php
                        $tot = $itemTotals[$item->id] ?? ['quantity' => 0, 'total_price' => 0];
                        @endphp

                        <div class="p-4 rounded border border-white/10 bg-black/20">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <div class="text-white font-semibold">{{ $item->name }}</div>
                                    <div class="text-white/60 text-sm">
                                        Fabric: {{ $item->fabric_name ?? '—' }}
                                    </div>

                                    @if($item->description)
                                    <div class="text-white/70 text-sm mt-2">
                                        {{ $item->description }}
                                    </div>
                                    @endif
                                </div>

                                <div class="text-right">
                                    <div class="text-white/60 text-xs">Single Price</div>
                                    <div class="text-white font-semibold">
                                        {{ number_format((float)($item->single_price ?? 0), 2) }}
                                    </div>

                                    <div class="mt-2 text-white/60 text-xs">Item Total</div>
                                    <div class="text-white font-semibold">
                                        {{ number_format((float)$tot['total_price'], 2) }}
                                    </div>

                                    <div class="mt-2 text-white/60 text-xs">Qty</div>
                                    <div class="text-white font-semibold">{{ (int)$tot['quantity'] }}</div>
                                </div>
                            </div>

                            <div class="mt-4 border-t border-white/10 pt-4">
                                <div class="text-white/70 text-sm mb-2">Sizes</div>

                                @if($item->itemSizes->count() === 0)
                                <div class="text-white/60 text-sm">—</div>
                                @else
                                <div class="grid grid-cols-12 gap-2">
                                    @foreach($item->itemSizes as $sz)
                                    <div class="col-span-12 md:col-span-4 p-3 rounded bg-black/30 border border-white/10">
                                        <div class="text-white font-semibold text-sm">
                                            {{ $sz->size?->name ?? ('#'.$sz->size_id) }}
                                        </div>
                                        <div class="text-white/60 text-xs">
                                            Qty: {{ $sz->quantity }}
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                @endif
                            </div>

                        </div>
                        @endforeach
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>