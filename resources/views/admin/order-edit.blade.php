<x-app-layout>
    <x-slot name="header">
        <h2 class="dash-title text-xl font-semibold leading-tight">
            {{ __('Edit Order') }}
        </h2>
    </x-slot>

    <div class="py-12 dashboard-wrap">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="dash-card overflow-hidden">
                <div class="p-6">

                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold">Edit Order</h3>

                        <a href="{{ route('admin.orders.index') }}"
                            class="inline-flex items-center px-4 py-2 btn-ghost text-xs uppercase tracking-widest">
                            Back
                        </a>
                    </div>

                    @if (session('error'))
                    <div class="mb-4 text-red-300 text-sm">{{ session('error') }}</div>
                    @endif

                    @if ($errors->any())
                    <div class="mb-4 text-red-300">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                            <li class="text-sm">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form method="POST" action="{{ route('admin.orders.update', $order) }}" class="space-y-6">
                        @csrf
                        @method('PATCH')

                        {{-- Customer --}}
                        <div class="p-4 rounded border border-white/10 bg-black/20">
                            <div class="text-white/70 text-sm mb-1">Customer</div>
                            <div class="text-white font-semibold">
                                {{ $order->customer_name }} ({{ $order->brand_name }})
                            </div>
                        </div>

                        {{-- Meeting --}}
                        <div>
                            <label class="block text-sm mb-1 text-white/80">Meeting (optional)</label>
                            <select name="meeting_id"
                                class="w-full px-3 py-2 rounded bg-black/30 border border-white/10 text-white">
                                <option value="">No meeting</option>
                                @foreach($meetings as $m)
                                <option value="{{ $m->id }}"
                                    @selected(old('meeting_id', $order->meeting_id) == $m->id)>
                                    #{{ $m->id }} - {{ optional($m->scheduled_date)->format('Y-m-d H:i') }} - {{ $m->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Phase --}}
                        <div>
                            <label class="block text-sm mb-1 text-white/80">Phase</label>
                            <select name="current_phase"
                                class="w-full px-3 py-2 rounded bg-black/30 border border-white/10 text-white" required>
                                @php
                                $current = old('current_phase', $order->current_phase);
                                // لو current_phase Enum حوّله value
                                if ($current instanceof \App\Enums\OrderPhaseEnum) {
                                $current = $current->value;
                                }
                                @endphp

                                @foreach($phases as $value => $label)
                                <option value="{{ $value }}" @selected($current===$value)>
                                    {{ $label }}
                                </option>
                                @endforeach

                            </select>
                        </div>

                        {{-- Requires Printing --}}
                        <label class="inline-flex items-center gap-2 text-white/80">
                            <input type="checkbox" name="requires_printing" value="1"
                                @checked(old('requires_printing', (bool)$order->requires_printing))>
                            Requires Printing
                        </label>

                        <hr class="border-white/10">

                        {{-- Items --}}
                        <div class="flex items-center justify-between">
                            <h4 class="text-white font-semibold">Items</h4>

                            <button type="button" id="btnAddItem"
                                class="inline-flex items-center px-4 py-2 btn-ghost text-xs uppercase tracking-widest">
                                + Add Item
                            </button>
                        </div>

                        <div id="itemsWrap" class="space-y-4"></div>

                        <div class="pt-2 flex items-center gap-2">
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 btn-brand text-xs uppercase tracking-widest">
                                Save Changes
                            </button>

                            <a href="{{ route('admin.orders.index') }}"
                                class="inline-flex items-center px-4 py-2 btn-ghost text-xs uppercase tracking-widest">
                                Cancel
                            </a>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>

    <script>

        const SIZES = @json($sizesForJs);
        const EXISTING_ITEMS = @json($orderItems);
        const OLD_ITEMS = @json(old('items', []));

        function sizeOptionsHTML(selectedId = "") {
            let html = `<option value="">Select size</option>`;
            for (const s of SIZES) {
                const sel = (String(s.id) === String(selectedId)) ? "selected" : "";
                html += `<option value="${s.id}" ${sel}>${s.name}</option>`;
            }
            return html;
        }

        const itemsWrap = document.getElementById('itemsWrap');
        const btnAddItem = document.getElementById('btnAddItem');

        function escapeHtml(str) {
            return String(str ?? "")
                .replaceAll("&", "&amp;")
                .replaceAll("<", "&lt;")
                .replaceAll(">", "&gt;")
                .replaceAll('"', "&quot;")
                .replaceAll("'", "&#039;");
        }

        function buildSizeRow(itemIndex, sizeIndex, old = {}) {
            const sizeId = old.size_id ?? "";
            const qty = old.quantity ?? 1;

            return `
                <div class="grid grid-cols-12 gap-3 items-end size-row" data-size-row>
                    <div class="col-span-7">
                        <label class="block text-sm mb-1 text-white/80">Size</label>
                        <select name="items[${itemIndex}][sizes][${sizeIndex}][size_id]"
                                class="w-full px-3 py-2 rounded bg-black/30 border border-white/10 text-white" required>
                            ${sizeOptionsHTML(sizeId)}
                        </select>
                    </div>

                    <div class="col-span-3">
                        <label class="block text-sm mb-1 text-white/80">Quantity</label>
                        <input type="number" min="1"
                               name="items[${itemIndex}][sizes][${sizeIndex}][quantity]"
                               value="${qty}"
                               class="w-full px-3 py-2 rounded bg-black/30 border border-white/10 text-white" required>
                    </div>

                    <div class="col-span-2">
                        <button type="button"
                                class="w-full inline-flex items-center justify-center px-3 py-2 btn-danger text-xs"
                                data-remove-size>
                            Remove
                        </button>
                    </div>
                </div>
            `;
        }

        function buildItemCard(itemIndex, old = {}) {
            const id = old.id ?? "";
            const name = old.name ?? "";
            const fabric = old.fabric_name ?? "";
            const hasPrinting = !!old.has_printing;
            const desc = old.description ?? "";
            const price = old.single_price ?? "";

            return `
                <div class="p-4 rounded border border-white/10 bg-black/20 item-card"
                     data-item-card data-item-index="${itemIndex}">

                    <div class="flex items-center justify-between mb-3">
                        <h5 class="text-white font-semibold">Item #${itemIndex + 1}</h5>
                        <button type="button" class="inline-flex items-center px-3 py-2 btn-danger text-xs"
                                data-remove-item>
                            Remove Item
                        </button>
                    </div>

                    <input type="hidden" name="items[${itemIndex}][id]" value="${escapeHtml(id)}">

                    <div class="grid grid-cols-12 gap-3">
                        <div class="col-span-6">
                            <label class="block text-sm mb-1 text-white/80">Item Name</label>
                            <input type="text" name="items[${itemIndex}][name]" value="${escapeHtml(name)}"
                                   class="w-full px-3 py-2 rounded bg-black/30 border border-white/10 text-white" required>
                        </div>

                        <div class="col-span-6">
                            <label class="block text-sm mb-1 text-white/80">Fabric Name</label>
                            <input type="text" name="items[${itemIndex}][fabric_name]" value="${escapeHtml(fabric)}"
                                   class="w-full px-3 py-2 rounded bg-black/30 border border-white/10 text-white">
                        </div>

                        <div class="col-span-12">
                            <label class="inline-flex items-center gap-2 text-white/80">
                                <input type="checkbox" name="items[${itemIndex}][has_printing]" value="1"
                                       ${hasPrinting ? "checked" : ""}>
                                Has Printing
                            </label>
                        </div>

                        <div class="col-span-8">
                            <label class="block text-sm mb-1 text-white/80">Description</label>
                            <textarea name="items[${itemIndex}][description]"
                                      class="w-full px-3 py-2 rounded bg-black/30 border border-white/10 text-white"
                                      rows="2">${escapeHtml(desc)}</textarea>
                        </div>

                        <div class="col-span-4">
                            <label class="block text-sm mb-1 text-white/80">Single Price</label>
                            <input type="number" step="0.01" min="0"
                                   name="items[${itemIndex}][single_price]" value="${escapeHtml(price)}"
                                   class="w-full px-3 py-2 rounded bg-black/30 border border-white/10 text-white" required>
                        </div>
                    </div>

                    <div class="mt-4 border-t border-white/10 pt-4">
                        <div class="flex items-center justify-between mb-3">
                            <h6 class="text-white/80 font-semibold text-sm">Sizes</h6>
                            <button type="button" class="inline-flex items-center px-3 py-2 btn-ghost text-xs"
                                    data-add-size>
                                + Add Size
                            </button>
                        </div>

                        <div class="space-y-3" data-sizes-wrap>
                            ${buildSizeRow(itemIndex, 0, (old.sizes && old.sizes[0]) ? old.sizes[0] : {})}
                        </div>
                    </div>
                </div>
            `;
        }

        function addItem(old = null) {
            const itemIndex = itemsWrap.querySelectorAll('[data-item-card]').length;
            const itemOld = old ?? {};

            itemsWrap.insertAdjacentHTML('beforeend', buildItemCard(itemIndex, itemOld));

            if (itemOld && Array.isArray(itemOld.sizes) && itemOld.sizes.length > 1) {
                const card = itemsWrap.lastElementChild;
                const sizesWrap = card.querySelector('[data-sizes-wrap]');

                for (let i = 1; i < itemOld.sizes.length; i++) {
                    sizesWrap.insertAdjacentHTML('beforeend', buildSizeRow(itemIndex, i, itemOld.sizes[i]));
                }
            }
        }

        // Delegation
        itemsWrap.addEventListener('click', (e) => {
            const card = e.target.closest('[data-item-card]');
            if (!card) return;

            if (e.target.matches('[data-remove-item]')) {
                card.remove();
                return;
            }

            if (e.target.matches('[data-add-size]')) {
                const itemIndex = card.getAttribute('data-item-index');
                const sizesWrap = card.querySelector('[data-sizes-wrap]');
                const sizeIndex = sizesWrap.querySelectorAll('[data-size-row]').length;
                sizesWrap.insertAdjacentHTML('beforeend', buildSizeRow(itemIndex, sizeIndex, {}));
                return;
            }

            if (e.target.matches('[data-remove-size]')) {
                const row = e.target.closest('[data-size-row]');
                row?.remove();
                return;
            }
        });

        btnAddItem.addEventListener('click', () => addItem());

        (function init() {
            if (Array.isArray(OLD_ITEMS) && OLD_ITEMS.length) {
                OLD_ITEMS.forEach(it => addItem(it));
                return;
            }

            if (Array.isArray(EXISTING_ITEMS) && EXISTING_ITEMS.length) {
                EXISTING_ITEMS.forEach(it => addItem(it));
                return;
            }

            addItem();
        })();
    </script>
</x-app-layout>