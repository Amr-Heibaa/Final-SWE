<x-app-layout>
    <x-slot name="header">
        <h2 class="dash-title text-xl font-semibold leading-tight">
            {{ __('Create Order') }}
        </h2>
    </x-slot>

    <div class="py-12 dashboard-wrap">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="dash-card overflow-hidden">
                <div class="p-6">

                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold">New Order</h3>

                        <a href="{{ route('admin.order-index') }}"
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

                    <form method="POST" action="{{ route('admin.orders.store') }}" class="space-y-6">
                        @csrf

                        {{-- Customer --}}
                        <div>
                            <label class="block text-sm mb-1 text-white/80">Customer</label>
                            <select name="customer_id"
                                    class="w-full px-3 py-2 rounded bg-black/30 border border-white/10 text-white" required>
                                <option value="">Select customer</option>
                                @foreach($customers as $c)
                                    <option value="{{ $c->id }}" @selected(old('customer_id') == $c->id)>
                                        {{ $c->name }} - {{ $c->email }} ({{ $c->brand_name }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Meeting (optional) --}}
                        <div>
                            <label class="block text-sm mb-1 text-white/80">Meeting (optional)</label>
                            <select name="meeting_id"
                                    class="w-full px-3 py-2 rounded bg-black/30 border border-white/10 text-white">
                                <option value="">No meeting</option>
                                @foreach($meetings as $m)
                                    <option value="{{ $m->id }}" @selected(old('meeting_id') == $m->id)>
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
                                @foreach($phases as $phase)
                                    <option value="{{ $phase->value }}" @selected(old('current_phase') == $phase->value)>
                                        {{ $phase->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Requires Printing --}}
                        <label class="inline-flex items-center gap-2 text-white/80">
                            <input type="checkbox" name="requires_printing" value="1"
                                   @checked(old('requires_printing'))>
                            Requires Printing
                        </label>

                        <hr class="border-white/10">

                        {{-- =========================
                           ITEMS (Dynamic)
                        ========================== --}}
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
                                Create Order
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

    {{-- Sizes data for JS --}}
    <script>
        const SIZES = @json($sizes->map(fn($s)=>['id'=>$s->id,'name'=>$s->name])->values());

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

        function buildSizeRow(itemIndex, sizeIndex, old = {}) {
            const sizeId  = old.size_id ?? "";
            const qty     = old.quantity ?? 1;

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
                        <button type="button" class="w-full inline-flex items-center justify-center px-3 py-2 btn-danger text-xs"
                                data-remove-size>
                            Remove
                        </button>
                    </div>
                </div>
            `;
        }

        function buildItemCard(itemIndex, old = {}) {
            const name        = old.name ?? "";
            const fabric      = old.fabric_name ?? "";
            const hasPrinting = !!old.has_printing;
            const desc        = old.description ?? "";
            const price       = old.single_price ?? "";

            return `
                <div class="p-4 rounded border border-white/10 bg-black/20 item-card" data-item-card data-item-index="${itemIndex}">
                    <div class="flex items-center justify-between mb-3">
                        <h5 class="text-white font-semibold">Item #${itemIndex + 1}</h5>
                        <button type="button" class="inline-flex items-center px-3 py-2 btn-danger text-xs"
                                data-remove-item>
                            Remove Item
                        </button>
                    </div>

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
                                <input type="checkbox" name="items[${itemIndex}][has_printing]" value="1" ${hasPrinting ? "checked" : ""}>
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
                            <input type="number" step="0.01" min="0" name="items[${itemIndex}][single_price]" value="${price}"
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

        function escapeHtml(str) {
            return String(str ?? "")
                .replaceAll("&", "&amp;")
                .replaceAll("<", "&lt;")
                .replaceAll(">", "&gt;")
                .replaceAll('"', "&quot;")
                .replaceAll("'", "&#039;");
        }

        function addItem(old = null) {
            const itemIndex = itemsWrap.querySelectorAll('[data-item-card]').length;

            // لو old موجود (رجوع validation) نزبط sizes indices
            let itemOld = old ?? null;
            if (itemOld && Array.isArray(itemOld.sizes) && itemOld.sizes.length) {
                // هنحط أول size بس هنا، والباقي هنضيفه بعد ما نحط الكارد
            }

            itemsWrap.insertAdjacentHTML('beforeend', buildItemCard(itemIndex, itemOld ?? {}));

            // لو فيه sizes متعددة من old → نضيفهم
            if (itemOld && Array.isArray(itemOld.sizes) && itemOld.sizes.length > 1) {
                const card = itemsWrap.lastElementChild;
                const sizesWrap = card.querySelector('[data-sizes-wrap]');

                // أول واحدة اتعملت بالفعل (index 0)
                for (let i = 1; i < itemOld.sizes.length; i++) {
                    sizesWrap.insertAdjacentHTML('beforeend', buildSizeRow(itemIndex, i, itemOld.sizes[i]));
                }
            }
        }

        // Events (delegation)
        itemsWrap.addEventListener('click', (e) => {
            const card = e.target.closest('[data-item-card]');
            if (!card) return;

            // Remove Item
            if (e.target.matches('[data-remove-item]')) {
                card.remove();
                // IMPORTANT: مش هنعيد ترقيم indices (عشان الموضوع كبير)
                // Laravel هيستقبل array keys زي ما هي حتى لو فيها gaps.
                return;
            }

            // Add Size
            if (e.target.matches('[data-add-size]')) {
                const itemIndex = card.getAttribute('data-item-index');
                const sizesWrap = card.querySelector('[data-sizes-wrap]');
                const sizeIndex = sizesWrap.querySelectorAll('[data-size-row]').length;
                sizesWrap.insertAdjacentHTML('beforeend', buildSizeRow(itemIndex, sizeIndex, {}));
                return;
            }

            // Remove Size
            if (e.target.matches('[data-remove-size]')) {
                const row = e.target.closest('[data-size-row]');
                row?.remove();
                return;
            }
        });

        btnAddItem.addEventListener('click', () => addItem());

        // Init: لو رجع old inputs بعد validation error
        (function init() {
            const oldItems = @json(old('items', []));
            if (Array.isArray(oldItems) && oldItems.length) {
                oldItems.forEach(it => addItem(it));
            } else {
                addItem(); // default 1 item
            }
        })();
    </script>
</x-app-layout>
