<x-app-layout>
    <x-slot name="header">
        <h2 class="dash-title text-xl font-semibold leading-tight">
            Meetings
        </h2>
    </x-slot>

    <div class="py-12 dashboard-wrap">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="dash-card p-6">

                @if($meetings->count() === 0)
                <p class="text-white/70">No meetings found.</p>
                @else
                <div class="overflow-x-auto">
                    <table class="dash-table min-w-full">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Customer</th>
                                <th>Phone</th>
                                <th>Brand</th>
                                <th>Scheduled</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($meetings as $meeting)
                            <tr>
                                <td>#{{ $meeting->id }}</td>
                                <td>{{ $meeting->customer->name ?? '-' }}</td>
                                <td>{{ $meeting->phone }}</td>
                                <td>{{ $meeting->brand_name }}</td>
                                <td>{{ optional($meeting->scheduled_date)->format('Y-m-d H:i') }}</td>
                                <td class="px-4 py-3 text-sm">
                                    <form method="POST" action="{{ route('admin.meetings.update-status', $meeting->id) }}" class="inline-flex items-center gap-2">
                                        @csrf
                                        @method('PATCH')

                                        <select name="status"
                                            class="px-3 py-2 rounded bg-black/30 border border-white/10 text-white text-xs">
                                            <option value="pending" @selected(($meeting->status?->value ?? $meeting->status) === 'pending')>Pending</option>
                                            <option value="completed" @selected(($meeting->status?->value ?? $meeting->status) === 'completed')>Completed</option>
                                            <option value="cancelled" @selected(($meeting->status?->value ?? $meeting->status) === 'cancelled')>Cancelled</option>
                                        </select>

                                        <button type="submit" class="px-3 py-2 btn-brand text-xs uppercase tracking-widest">
                                            Update
                                        </button>
                                    </form>
                                </td>

                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $meetings->links() }}
                </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>