{{-- resources/views/Meeting/form.blade.php --}}
<x-app-layout>

    <div class="min-h-screen bg-[#101414] pt-20">

        {{-- Hero Banner --}}
        <section class="relative h-[400px] flex items-center justify-center overflow-hidden">
            <div class="absolute inset-0">
                <img
                    src="https://images.unsplash.com/photo-1647427060118-4911c9821b82?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxpbmR1c3RyaWFsJTIwbWFudWZhY3R1cmluZ3xlbnwxfHx8fDE3NjA2MzU1NzB8MA&ixlib=rb-4.1.0&q=80&w=1080"
                    alt="Contact Us"
                    class="w-full h-full object-cover" />
                <div class="absolute inset-0 bg-[#101414]/80"></div>
            </div>

            <div class="relative z-10 text-center px-6">
                <h1 class="text-5xl md:text-6xl text-white">
                    Contact <span class="text-[#8EE2D2]">Us</span>
                </h1>
                <p class="text-xl text-white/70 mt-4">
                    Get in touch with us to discuss your manufacturing needs
                </p>
            </div>
        </section>

        {{-- Contact Section (IMPORTANT: id="contact") --}}
        <section id="contact" class="py-24 bg-[#101414]">
            <div class="max-w-7xl mx-auto px-6 lg:px-8">
                <div class="grid md:grid-cols-2 gap-12">

                    {{-- Contact Info --}}
                    <div class="space-y-8">
                        <div>
                            <h2 class="text-3xl text-white mb-6">Get In Touch</h2>
                            <p class="text-white/70 mb-8">
                                We're here to help with your manufacturing needs. Reach out to us through any of
                                the following channels and our team will respond promptly.
                            </p>
                        </div>

                        <div class="space-y-6">
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 bg-[#8EE2D2]/10 rounded-lg flex items-center justify-center flex-shrink-0">
                                    {{-- MapPin icon --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-[#8EE2D2]">
                                        <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path>
                                        <circle cx="12" cy="10" r="3"></circle>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-white mb-1">Address</h3>
                                    <p class="text-white/60">
                                        123 Manufacturing Street<br>
                                        Industry City, IC 12345<br>
                                        United States
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 bg-[#8EE2D2]/10 rounded-lg flex items-center justify-center flex-shrink-0">
                                    {{-- Phone icon --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-[#8EE2D2]">
                                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.79 19.79 0 0 1 2.08 4.18 2 2 0 0 1 4.06 2h3a2 2 0 0 1 2 1.72c.12.81.3 1.6.54 2.36a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.72-1.06a2 2 0 0 1 2.11-.45c.76.24 1.55.42 2.36.54A2 2 0 0 1 22 16.92Z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-white mb-1">Phone</h3>
                                    <p class="text-white/60">+1 (555) 123-4567</p>
                                    <p class="text-white/60">+1 (555) 987-6543</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 bg-[#8EE2D2]/10 rounded-lg flex items-center justify-center flex-shrink-0">
                                    {{-- Mail icon --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-[#8EE2D2]">
                                        <rect width="20" height="16" x="2" y="4" rx="2"></rect>
                                        <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-white mb-1">Email</h3>
                                    <p class="text-white/60">info@manufactrack.com</p>
                                    <p class="text-white/60">support@manufactrack.com</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 bg-[#8EE2D2]/10 rounded-lg flex items-center justify-center flex-shrink-0">
                                    {{-- Clock icon --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-[#8EE2D2]">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <path d="M12 6v6l4 2"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-white mb-1">Business Hours</h3>
                                    <p class="text-white/60">Monday - Friday: 9:00 AM - 6:00 PM</p>
                                    <p class="text-white/60">Saturday: 10:00 AM - 4:00 PM</p>
                                    <p class="text-white/60">Sunday: Closed</p>
                                </div>
                            </div>
                        </div>

                        <div class="w-full h-64 bg-[#0a0a0a] rounded-lg border border-[#8EE2D2]/20 flex items-center justify-center overflow-hidden">
                            <p class="text-white/40">Google Maps Embed</p>
                        </div>
                    </div>

                    {{-- Contact Form --}}
                    <div class="bg-[#0a0a0a] p-8 rounded-lg border border-[#8EE2D2]/20">
                        <h2 class="text-3xl text-white mb-6">Send Us a Message</h2>

                        @if (session('success'))
                        <div class="mb-6 rounded-lg border border-[#8EE2D2]/30 bg-[#8EE2D2]/10 p-4 text-white/80">
                            {{ session('success') }}
                        </div>
                        @endif

<form method="POST" action="{{ route('meetings.store') }}">
                            <div>
                                <label for="name" class="block text-white mb-2">Name *</label>
                                <input id="name" name="name" value="{{ old('name') }}" required
                                    placeholder="Your name"
                                    class="w-full rounded-md bg-[#101414] border border-[#8EE2D2]/20 text-white px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#8EE2D2]/40 focus:border-[#8EE2D2]" />
                                @error('name') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-white mb-2">Email *</label>
                                <input id="email" name="email" type="email" value="{{ old('email') }}" required
                                    placeholder="your@email.com"
                                    class="w-full rounded-md bg-[#101414] border border-[#8EE2D2]/20 text-white px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#8EE2D2]/40 focus:border-[#8EE2D2]" />
                                @error('email') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="subject" class="block text-white mb-2">Subject *</label>
                                <input id="subject" name="subject" value="{{ old('subject') }}" required
                                    placeholder="How can we help?"
                                    class="w-full rounded-md bg-[#101414] border border-[#8EE2D2]/20 text-white px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#8EE2D2]/40 focus:border-[#8EE2D2]" />
                                @error('subject') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="message" class="block text-white mb-2">Message *</label>
                                <textarea id="message" name="message" rows="6" required
                                    placeholder="Tell us about your project..."
                                    class="w-full rounded-md bg-[#101414] border border-[#8EE2D2]/20 text-white px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#8EE2D2]/40 focus:border-[#8EE2D2] resize-none">{{ old('message') }}</textarea>
                                @error('message') <p class="mt-2 text-sm text-red-400">{{ $message }}</p> @enderror
                            </div>

                            <button type="submit"
                                class="w-full rounded-md bg-[#8EE2D2] text-[#101414] hover:bg-[#8EE2D2]/90 py-4 font-medium">
                                Send Message
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </section>
    </div>
</x-app-layout>