<x-app-layout title="My Orders - AAA Studios">
    <div class="site-dark">
        <div class="spacer-top"></div>

        @php
            // Mapping مراحل الـ OrderPhaseEnum (زي الشكل)
            $phaseMap = [
                'pending'   => ['label' => 'Order Received', 'progress' => 0,   'tone' => 'yellow'],
                'cutting'   => ['label' => 'Cutting',        'progress' => 25,  'tone' => 'blue'],
                'printing'  => ['label' => 'Printing',       'progress' => 35,  'tone' => 'cyan'],
                'sewing'    => ['label' => 'Sewing',         'progress' => 50,  'tone' => 'purple'],
                'packaging' => ['label' => 'Packaging',      'progress' => 75,  'tone' => 'orange'],
                'delivery'  => ['label' => 'In Delivery',    'progress' => 90,  'tone' => 'cyan'],
                'completed' => ['label' => 'Completed',      'progress' => 100, 'tone' => 'green'],
                'cancelled' => ['label' => 'Cancelled',      'progress' => 0,   'tone' => 'yellow'],
            ];

            // Timeline steps
            $steps = ['pending','cutting','sewing','packaging','completed'];

            $getDesign = function($order){
                return optional($order->items->first())->name ?? ('Order #' . $order->id);
            };

            $getQty = function($order){
                $total = 0;
                foreach ($order->items as $item) {
                    foreach ($item->itemSizes as $sz) {
                        $total += (int) $sz->quantity;
                    }
                }
                return $total;
            };

            $getEstimated = function($order){
                return optional($order->meeting)->scheduled_date
                    ? \Illuminate\Support\Carbon::parse($order->meeting->scheduled_date)->toDateString()
                    : '—';
            };
        @endphp

        <main class="orders-page">
            <div class="orders-container">

                <header class="orders-head">
                    <h1>My Orders</h1>
                    <p>Track your manufacturing orders in real-time</p>
                </header>

                <div class="orders-toolbar">
                    <div class="orders-search">
                        <span class="orders-search-ic" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                                <path d="M21 21l-4.3-4.3"></path>
                                <circle cx="11" cy="11" r="7"></circle>
                            </svg>
                        </span>
                        <input id="ordersSearch" type="text" placeholder="Search by order ID, item name, or customer..." autocomplete="off" />
                    </div>

                    <div class="orders-filter">
                        <span class="orders-filter-ic" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                                <path d="M22 3H2l8 9v7l4 2v-9l8-9z"></path>
                            </svg>
                        </span>

                        <select id="ordersStatus">
                            <option value="all">All Orders</option>
                            @foreach($phaseMap as $key => $v)
                                <option value="{{ $key }}">{{ ucfirst($key) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="orders-empty" id="ordersEmpty" style="display:none;">
                    <div class="orders-empty-ic" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                            <path d="M21 8l-9-5-9 5 9 5 9-5z"></path>
                            <path d="M3 8v8l9 5 9-5V8"></path>
                            <path d="M12 13v8"></path>
                        </svg>
                    </div>
                    <h3 id="ordersEmptyTitle">No orders found</h3>
                    <p id="ordersEmptyText">Try adjusting your search or filter criteria</p>
                </div>

                <div class="orders-list" id="ordersList">
                    @forelse($orders as $order)
                        @php
                            // لو current_phase Enum لازم ناخد value (استخدم اسم enum الحقيقي عندك)
                            $phaseKey = $order->current_phase instanceof \App\Enums\OrderPhaseEnum
                                ? $order->current_phase->value
                                : (string) $order->current_phase;

                            $st = $phaseMap[$phaseKey] ?? $phaseMap['pending'];

                            $design    = $getDesign($order);
                            $qty       = $getQty($order);
                            $brandName = optional($order->customer)->name ?? '—';
                            $orderDate = optional($order->created_at)->toDateString() ?? '—';
                            $estimated = $getEstimated($order);
                        @endphp

                        <article class="order-card"
                                 data-order
                                 data-phase="{{ $phaseKey }}"
                                 data-id="{{ (string)$order->id }}"
                                 data-design="{{ strtolower($design) }}"
                                 data-brand="{{ strtolower($brandName) }}">

                            <div class="order-row">
                                <div class="order-main">
                                    <div class="order-title">
                                        <h3 class="order-design">{{ $design }}</h3>
                                        <span class="status-badge tone-{{ $st['tone'] }}">
                                            <span class="status-dot" aria-hidden="true"></span>
                                            {{ $st['label'] }}
                                        </span>
                                    </div>

                                    <p class="order-meta">
                                        Order #{{ $order->id }} • {{ $brandName }} • {{ $qty }} units
                                    </p>

                                    <div class="order-progress-block">
                                        <div class="order-progress-top">
                                            <span>Production Progress</span>
                                            <span class="order-progress-val">{{ $st['progress'] }}%</span>
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar" style="width: {{ $st['progress'] }}%;"></div>
                                        </div>
                                    </div>

                                    <div class="order-dates">
                                        <div class="order-date">
                                            <p>Order Date</p>
                                            <strong>{{ $orderDate }}</strong>
                                        </div>
                                        <div class="order-date">
                                            <p>Estimated Delivery</p>
                                            <strong>{{ $estimated }}</strong>
                                        </div>
                                    </div>
                                </div>

                             
                            </div>

                            <div class="order-timeline">
                                @foreach($steps as $step)
                                    @php
                                        $info = $phaseMap[$step];
                                        $done = $st['progress'] >= $info['progress'];
                                        $isCurrent = $phaseKey === $step;
                                    @endphp

                                    <div class="timeline-step {{ $done ? 'is-done' : '' }} {{ $isCurrent ? 'is-current' : '' }}">
                                        <div class="timeline-dot" aria-hidden="true"></div>
                                        <div class="timeline-label">{{ $info['label'] }}</div>
                                    </div>
                                @endforeach
                            </div>

                        </article>
                    @empty
                        <script>window.__NO_ORDERS__ = true;</script>
                    @endforelse
                </div>

                <div style="margin-top:18px;">
                    {{ $orders->links() }}
                </div>
            </div>

            <script>
                const searchEl = document.getElementById('ordersSearch');
                const phaseEl  = document.getElementById('ordersStatus');
                const emptyEl  = document.getElementById('ordersEmpty');
                const emptyTitle = document.getElementById('ordersEmptyTitle');
                const emptyText  = document.getElementById('ordersEmptyText');

                function applyOrdersFilter() {
                    const q = (searchEl.value || '').trim().toLowerCase();
                    const p = phaseEl.value;

                    const cards = Array.from(document.querySelectorAll('[data-order]'));
                    let shown = 0;

                    cards.forEach(card => {
                        const matchesPhase = (p === 'all' || card.dataset.phase === p);
                        const matchesSearch = !q || (
                            card.dataset.id.toLowerCase().includes(q) ||
                            card.dataset.design.includes(q) ||
                            card.dataset.brand.includes(q)
                        );

                        const show = matchesPhase && matchesSearch;
                        card.style.display = show ? '' : 'none';
                        if (show) shown++;
                    });

                    const shouldShowEmpty = (cards.length > 0 && shown === 0) || (window.__NO_ORDERS__ === true);
                    emptyEl.style.display = shouldShowEmpty ? '' : 'none';

                    if (shouldShowEmpty) {
                        if (cards.length === 0 || window.__NO_ORDERS__ === true) {
                            emptyTitle.textContent = "No orders yet";
                            emptyText.textContent  = "Start your first manufacturing project with us";
                        } else {
                            emptyTitle.textContent = "No orders found";
                            emptyText.textContent  = "Try adjusting your search or filter criteria";
                        }
                    }
                }

                searchEl?.addEventListener('input', applyOrdersFilter);
                phaseEl?.addEventListener('change', applyOrdersFilter);
                applyOrdersFilter();
            </script>
        </main>
    </div>
</x-app-layout>
