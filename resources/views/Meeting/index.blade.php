{{-- resources/views/Meeting/index.blade.php --}}
<x-app-layout>
    <div class="min-h-screen bg-[#101414] pt-20">

        {{-- Hero --}}
        <section class="relative h-[280px] flex items-center justify-center overflow-hidden">
            <div class="absolute inset-0">
                <img
                    src="https://images.unsplash.com/photo-1647427060118-4911c9821b82?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxpbmR1c3RyaWFsJTIwbWFudWZhY3R1cmluZ3xlbnwxfHx8fDE3NjA2MzU1NzB8MA&ixlib=rb-4.1.0&q=80&w=1080"
                    alt="My Meetings"
                    class="w-full h-full object-cover" />
                <div class="absolute inset-0 bg-[#101414]/80"></div>
            </div>

            <div class="relative z-10 text-center px-6">
                <h1 class="text-5xl md:text-6xl text-white">
                    My <span class="text-[#8EE2D2]">Meetings</span>
                </h1>
                <p class="text-xl text-white/70 mt-4">
                    Track your meeting requests and their status.
                </p>
            </div>
        </section>

        <section class="py-14 bg-[#101414]">
            <div class="max-w-7xl mx-auto px-6 lg:px-8">

                {{-- Header actions --}}
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
                    <div>
                        <h2 class="text-2xl text-white">Meetings</h2>
                        <p class="text-white/60">Showing your latest requests</p>
                    </div>

                    <a href="{{ route('meetings.create') }}"
                       class="inline-flex items-center justify-center rounded-md bg-[#8EE2D2] text-[#101414] hover:bg-[#8EE2D2]/90 px-5 py-3 font-medium">
                        + Create Meeting
                    </a>
                </div>

                @if (session('success'))
                    <div class="mb-6 rounded-lg border border-[#8EE2D2]/30 bg-[#8EE2D2]/10 p-4 text-white/80">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($meetings->count() === 0)
                    <div class="bg-[#0a0a0a] p-8 rounded-lg border border-[#8EE2D2]/20 text-white/80">
                        <p class="mb-4">You don’t have any meetings yet.</p>
                        <a href="{{ route('meetings.create') }}"
                           class="inline-flex items-center justify-center rounded-md bg-[#8EE2D2] text-[#101414] hover:bg-[#8EE2D2]/90 px-5 py-3 font-medium">
                            Create your first meeting
                        </a>
                    </div>
                @else
                    {{-- Cards --}}
                    <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-6">
                        @foreach ($meetings as $meeting)
                            @php
                                $status = $meeting->status?->value ?? 'pending';
                                $statusClass =
                                    $status === 'completed' ? 'bg-green-500/15 border-green-500/30 text-green-200' :
                                    ($status === 'cancelled' ? 'bg-red-500/15 border-red-500/30 text-red-200' :
                                    'bg-yellow-500/15 border-yellow-500/30 text-yellow-200');
                            @endphp

                            <div class="bg-[#0a0a0a] rounded-lg border border-[#8EE2D2]/20 overflow-hidden">
                                <div class="p-6">
                                    <div class="flex items-start justify-between gap-3">
                                        <div>
                                            <h3 class="text-xl text-white">{{ $meeting->brand_name }}</h3>
                                            <p class="text-white/60 mt-1">{{ $meeting->name }} — {{ $meeting->phone }}</p>
                                        </div>

                                        <span class="px-3 py-1 rounded-md border text-sm {{ $statusClass }}">
                                            {{ ucfirst($status) }}
                                        </span>
                                    </div>

                                    <div class="mt-5 text-white/70 space-y-2">
                                        <div class="flex items-center justify-between gap-3">
                                            <span class="text-white/50">Scheduled</span>
                                            <span class="text-white/80">
                                                {{ optional($meeting->scheduled_date)->format('Y-m-d H:i') }}
                                            </span>
                                        </div>

                                        <div class="flex items-center justify-between gap-3">
                                            <span class="text-white/50">Meeting ID</span>
                                            <span class="text-white/80">#{{ $meeting->id }}</span>
                                        </div>
                                    </div>

                                    <div class="mt-6 flex flex-col sm:flex-row gap-3">
                                        <a href="{{ route('meetings.show', $meeting) }}"
                                           class="w-full text-center rounded-md border border-[#8EE2D2]/30 px-4 py-2 text-white hover:bg-[#8EE2D2]/10">
                                            View
                                        </a>

                                        <a href="{{ route('meetings.edit', $meeting) }}"
                                           class="w-full text-center rounded-md bg-[#8EE2D2] px-4 py-2 text-[#101414] hover:bg-[#8EE2D2]/90 font-medium">
                                            Edit
                                        </a>
                                    </div>

                                    <form method="POST" action="{{ route('meetings.destroy', $meeting) }}" class="mt-3">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            onclick="return confirm('Are you sure you want to delete this meeting?');"
                                            class="w-full rounded-md border border-red-400/40 bg-red-400/10 text-red-200 hover:bg-red-400/20 px-4 py-2 font-medium">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-10">
                        <div class="rounded-lg border border-[#8EE2D2]/20 bg-[#0a0a0a] p-4">
                            {{ $meetings->links() }}
                        </div>
                    </div>
                @endif

            </div>
        </section>
    </div>
</x-app-layout>
