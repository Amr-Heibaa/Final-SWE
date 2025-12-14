{{-- resources/views/Meeting/edit.blade.php --}}
<x-app-layout>
    <div class="min-h-screen bg-[#101414] pt-20">

        {{-- Hero Banner --}}
        <section class="relative h-[320px] flex items-center justify-center overflow-hidden">
            <div class="absolute inset-0">
                <img
                    src="https://images.unsplash.com/photo-1647427060118-4911c9821b82?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxpbmR1c3RyaWFsJTIwbWFudWZhY3R1cmluZ3xlbnwxfHx8fDE3NjA2MzU1NzB8MA&ixlib=rb-4.1.0&q=80&w=1080"
                    alt="Edit Meeting"
                    class="w-full h-full object-cover" />
                <div class="absolute inset-0 bg-[#101414]/80"></div>
            </div>

            <div class="relative z-10 text-center px-6">
                <h1 class="text-5xl md:text-6xl text-white">
                    Edit <span class="text-[#8EE2D2]">Meeting</span>
                </h1>
                <p class="text-xl text-white/70 mt-4">
                    Update your meeting request details.
                </p>
            </div>
        </section>

        <section class="py-24 bg-[#101414]">
            <div class="max-w-4xl mx-auto px-6 lg:px-8">

                <div class="bg-[#0a0a0a] p-8 rounded-lg border border-[#8EE2D2]/20">
                    <div class="flex items-center justify-between gap-4 mb-6">
                        <h2 class="text-3xl text-white">Edit Meeting</h2>

                        <a href="{{ route('meetings.show', $meeting) }}"
                           class="rounded-md border border-[#8EE2D2]/30 px-4 py-2 text-white hover:bg-[#8EE2D2]/10">
                            Back to Details
                        </a>
                    </div>

                    @if (session('success'))
                        <div class="mb-6 rounded-lg border border-[#8EE2D2]/30 bg-[#8EE2D2]/10 p-4 text-white/80">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-6 rounded-lg border border-red-400/30 bg-red-400/10 p-4 text-white/80">
                            <p class="mb-2 font-medium">Please fix the following errors:</p>
                            <ul class="list-disc pl-5 text-white/70 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('meetings.update', $meeting) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="name" class="block text-white mb-2">Name *</label>
                            <input
                                id="name"
                                name="name"
                                value="{{ old('name', $meeting->name) }}"
                                required
                                class="w-full rounded-md bg-[#101414] border border-[#8EE2D2]/20 text-gray-800 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#8EE2D2]/40 focus:border-[#8EE2D2]" />
                            @error('name') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="phone" class="block text-white mb-2">Phone *</label>
                            <input
                                id="phone"
                                name="phone"
                                value="{{ old('phone', $meeting->phone) }}"
                                required
                                class="w-full rounded-md bg-[#101414] border border-[#8EE2D2]/20 text-gray-800 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#8EE2D2]/40 focus:border-[#8EE2D2]" />
                            @error('phone') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="brand_name" class="block text-white mb-2">Brand Name *</label>
                            <input
                                id="brand_name"
                                name="brand_name"
                                value="{{ old('brand_name', $meeting->brand_name) }}"
                                required
                                class="w-full rounded-md bg-[#101414] border border-[#8EE2D2]/20 text-gray-800 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#8EE2D2]/40 focus:border-[#8EE2D2]" />
                            @error('brand_name') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="scheduled_date" class="block text-white mb-2">Scheduled Date *</label>

                            @php
                                // datetime-local needs: YYYY-MM-DDTHH:MM
                                $scheduledValue = old(
                                    'scheduled_date',
                                    optional($meeting->scheduled_date)->format('Y-m-d\TH:i')
                                );
                            @endphp

                            <input
                                id="scheduled_date"
                                name="scheduled_date"
                                type="datetime-local"
                                value="{{ $scheduledValue }}"
                                required
                                class="w-full rounded-md bg-[#101414] border border-[#8EE2D2]/20 text-gray-800 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#8EE2D2]/40 focus:border-[#8EE2D2]" />
                            @error('scheduled_date') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                        </div>

                        {{-- Status Admin Only --}}
                        <div class="rounded-lg border border-[#8EE2D2]/20 bg-[#101414] p-4 text-white/70">
                            <p class="text-white/80 font-medium">Status</p>
                            <p class="mt-1">
                                {{ $meeting->status?->value ?? 'pending' }}
                                <span class="text-white/40">— (Admin only)</span>
                            </p>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-3">
                            <button type="submit"
                                class="w-full sm:w-auto rounded-md bg-[#8EE2D2] text-[#101414] hover:bg-[#8EE2D2]/90 py-3 px-6 font-medium">
                                Save Changes
                            </button>

                            <a href="{{ route('meetings.index') }}"
                               class="w-full sm:w-auto text-center rounded-md border border-[#8EE2D2]/30 px-6 py-3 text-white hover:bg-[#8EE2D2]/10">
                                Back to My Meetings
                            </a>
                        </div>
                    </form>

                    {{-- Delete button--}}
                    <form method="POST" action="{{ route('meetings.destroy', $meeting) }}" class="mt-10">
                        @csrf
                        @method('DELETE')

                        <button type="submit"
                            onclick="return confirm('Are you sure you want to delete this meeting?');"
                            class="w-full rounded-md border border-red-400/40 bg-red-400/10 text-red-200 hover:bg-red-400/20 py-3 font-medium">
                            Delete Meeting
                        </button>
                    </form>

                </div>
            </div>
        </section>

    </div>
</x-app-layout>
