<x-app-layout>
    <div class="min-h-screen bg-[#101414] pt-20">
        <div class="max-w-3xl mx-auto px-6 lg:px-8">
            <div class="bg-[#0a0a0a] p-8 rounded-lg border border-[#8EE2D2]/20">
                <h1 class="text-3xl text-white mb-6">Meeting Details</h1>

                @if (session('success'))
                    <div class="mb-6 rounded-lg border border-[#8EE2D2]/30 bg-[#8EE2D2]/10 p-4 text-white/80">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="space-y-4 text-white/80">
                    <div>
                        <p class="text-white/50">Name</p>
                        <p class="text-lg">{{ $meeting->name }}</p>
                    </div>

                    <div>
                        <p class="text-white/50">Phone</p>
                        <p class="text-lg">{{ $meeting->phone }}</p>
                    </div>

                    <div>
                        <p class="text-white/50">Brand Name</p>
                        <p class="text-lg">{{ $meeting->brand_name }}</p>
                    </div>

                    <div>
                        <p class="text-white/50">Scheduled Date</p>
                        <p class="text-lg">{{ optional($meeting->scheduled_date)->format('Y-m-d H:i') }}</p>
                    </div>

                    @php
                        $status = $meeting->status?->value ?? 'pending';
                    @endphp

                    <div>
                        <p class="text-white/50">Status</p>
                        <p class="text-lg">{{ ucfirst($status) }}</p>
                        <p class="text-white/40 text-sm mt-1">Status is managed by admin.</p>
                    </div>
                </div>

                <div class="mt-8 flex gap-3">
                    <a href="{{ route('meetings.index') }}"
                       class="rounded-md border border-[#8EE2D2]/30 px-4 py-2 text-white hover:bg-[#8EE2D2]/10">
                        Back to My Meetings
                    </a>

                    <a href="{{ route('meetings.edit', $meeting) }}"
                       class="rounded-md bg-[#8EE2D2] px-4 py-2 text-[#101414] hover:bg-[#8EE2D2]/90 font-medium">
                        Edit
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
