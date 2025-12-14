{{-- resources/views/Meeting/form.blade.php --}}
<x-app-layout>
    <div class="min-h-screen bg-[#101414] pt-20">

        {{-- Hero Banner --}}
        <section class="relative h-[400px] flex items-center justify-center overflow-hidden">
            <div class="absolute inset-0">
                <img
                    src="https://scontent.cdninstagram.com/v/t51.82787-15/582106060_17928519564138311_3370676791890115320_n.jpg?stp=dst-jpg_e35_tt6&_nc_cat=110&ig_cache_key=Mzc2NTAyNTQ5NTgzNDk3MjExNw%3D%3D.3-ccb7-5&ccb=7-5&_nc_sid=58cdad&efg=eyJ2ZW5jb2RlX3RhZyI6InhwaWRzLjEyMDB4MTUwMC5zZHIuQzMifQ%3D%3D&_nc_ohc=wvrdqcLtxN4Q7kNvwEQ6DJT&_nc_oc=Admk5io9MUwz9ULPuKFBp8pBucnZveB3dtGSuOtmqYxMoiPCSMzr8ArONX0k8l_L4jI&_nc_ad=z-m&_nc_cid=0&_nc_zt=23&_nc_ht=scontent.cdninstagram.com&_nc_gid=7Ho2jAYgkMuM-8rjHJdZeA&oh=00_AflYlJdGRiW_V6v-x2E8r2v9C_l9L4cDjOJs3qmGAk-1Jg&oe=6944EB1B"
                    class="w-full h-full object-cover" />
                <div class="absolute inset-0 bg-[#101414]/80"></div>
            </div>

            <div class="relative z-10 text-center px-6">
                <h1 class="text-5xl md:text-6xl text-white">
                    Create <span class="text-[#8EE2D2]">Meeting</span>
                </h1>
                <p class="text-xl text-white/70 mt-4">
                    Submit your meeting details and we’ll get back to you.
                </p>
            </div>
        </section>

        {{-- Meeting Section --}}
        <section id="meeting" class="py-24 bg-[#101414]">
            <div class="max-w-7xl mx-auto px-6 lg:px-8">
                <div class="grid md:grid-cols-2 gap-12">

                    {{-- Info --}}
                    <div class="space-y-8">
                        <div>
                            <h2 class="text-3xl text-white mb-6">Meeting Details</h2>
                            <p class="text-white/70 mb-8">
                                Fill in the form with your contact details and preferred schedule.
                                You can also set the meeting status (optional).
                            </p>
                        </div>

                        <div class="space-y-6">
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 bg-[#8EE2D2]/10 rounded-lg flex items-center justify-center flex-shrink-0">
                                    {{-- Calendar icon --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-[#8EE2D2]">
                                        <path d="M8 2v4"></path>
                                        <path d="M16 2v4"></path>
                                        <rect width="18" height="18" x="3" y="4" rx="2"></rect>
                                        <path d="M3 10h18"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-white mb-1">Scheduling</h3>
                                    <p class="text-white/60">
                                        Choose a future date and time for the meeting.
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 bg-[#8EE2D2]/10 rounded-lg flex items-center justify-center flex-shrink-0">
                                    {{-- Shield icon --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-[#8EE2D2]">
                                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-white mb-1">Privacy</h3>
                                    <p class="text-white/60">
                                        Your data is used only for meeting coordination.
                                    </p>
                                </div>
                            </div>
                        </div>


                        <div class="w-full h-64 rounded-lg border border-[#8EE2D2]/20 overflow-hidden">
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m17!1m12!1m3!1d3450.6977927490293!2d31.36935207555708!3d30.13145997487996!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m2!1m1!2zMzDCsDA3JzUzLjMiTiAzMcKwMjInMTguOSJF!5e0!3m2!1sen!2seg!4v1765745532092!5m2!1sen!2seg"
                                class="w-full h-full"
                                style="border:0;"
                                allowfullscreen
                                loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade">
                            </iframe>
                        </div>


                    </div>

                    {{-- Meeting Form --}}
                    <div class="bg-[#0a0a0a] p-8 rounded-lg border border-[#8EE2D2]/20">
                        <h2 class="text-3xl text-white mb-6">Create Meeting</h2>

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

                        <form method="POST" action="{{ route('meetings.store') }}" class="space-y-6">
                            @csrf

                            <div>
                                <label for="name" class="block text-white mb-2">Name *</label>
                                <input
                                    id="name"
                                    name="name"
                                    value="{{ old('name') }}"
                                    required
                                    placeholder="Your name"
                                    class="w-full rounded-md bg-[#101414] border border-[#8EE2D2]/20 text-gray-800 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#8EE2D2]/40 focus:border-[#8EE2D2]" />
                                @error('name') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="phone" class="block text-white mb-2">Phone *</label>
                                <input
                                    id="phone"
                                    name="phone"
                                    value="{{ old('phone') }}"
                                    required
                                    placeholder="+2010xxxxxxx"
                                    class="w-full rounded-md bg-[#101414] border border-[#8EE2D2]/20 text-gray-800 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#8EE2D2]/40 focus:border-[#8EE2D2]" />
                                @error('phone') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="brand_name" class="block text-white mb-2">Brand Name *</label>
                                <input
                                    id="brand_name"
                                    name="brand_name"
                                    value="{{ old('brand_name') }}"
                                    required
                                    placeholder="Your brand name"
                                    class="w-full rounded-md bg-[#101414] border border-[#8EE2D2]/20 text-gray-800 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#8EE2D2]/40 focus:border-[#8EE2D2]" />
                                @error('brand_name') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="scheduled_date" class="block text-white mb-2">Scheduled Date *</label>
                                <input
                                    id="scheduled_date"
                                    name="scheduled_date"
                                    type="datetime-local"
                                    value="{{ old('scheduled_date') }}"
                                    required
                                    class="w-full rounded-md bg-[#101414] border border-[#8EE2D2]/20 text-gray-800 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#8EE2D2]/40 focus:border-[#8EE2D2]" />
                                @error('scheduled_date') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                            </div>

                            <!-- <div>
                                <label for="status" class="block text-white mb-2">Status</label>
                                <select
                                    id="status"
                                    name="status"
                                    class="w-full rounded-md bg-[#101414] border border-[#8EE2D2]/20 text-gray-800 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#8EE2D2]/40 focus:border-[#8EE2D2]">
                                    <option value="pending" @selected(old('status', 'pending' )==='pending' )>Pending</option>
                                    <option value="completed" @selected(old('status')==='completed' )>Completed</option>
                                    <option value="cancelled" @selected(old('status')==='cancelled' )>Cancelled</option>
                                </select>
                                @error('status') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                            </div> -->

                            <button type="submit"
                                class="w-full rounded-md bg-[#8EE2D2] text-[#101414] hover:bg-[#8EE2D2]/90 py-4 font-medium">
                                Create Meeting
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </section>
    </div>
</x-app-layout>