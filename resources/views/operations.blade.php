<x-app-layout title="Operations - ManufacTrack">

    {{-- Your custom navbar (same as welcome/about) --}}
    <header class="nav">
        <div class="container">
            <div class="nav-row">
                <a class="brand" href="{{ route('home') }}">
                    <div class="brand-badge" aria-hidden="true"><span>M</span></div>
                    <span>ManufacTrack</span>
                </a>

                <nav class="nav-links" aria-label="Primary">
                    <a class="nav-link" href="{{ route('home') }}">Home</a>
                    <a class="nav-link" href="{{ route('about') }}">Our Story</a>
                    <a class="nav-link" href="{{ route('operations') }}">Operations</a>
                    <a class="nav-link" href="{{ route('media') }}">Media</a>
                    <a class="nav-link" href="{{ route('home') }}#contact">Contact</a>
                </nav>

                <div class="nav-actions">
                    @if (Route::has('login'))
                        <div class="auth-actions">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="btn btn-outline">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-outline">Login</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="btn btn-outline">Register</a>
                                @endif
                            @endauth
                        </div>
                    @endif

                    <button class="icon-btn menu-btn" id="mobileMenuBtn" aria-label="Toggle menu" aria-expanded="false" type="button">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                            <path d="M3 6h18M3 12h18M3 18h18"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="mobile-panel" id="mobilePanel">
                <div class="mobile-links">
                    <a class="mobile-link" href="{{ route('home') }}">Home</a>
                    <a class="mobile-link" href="{{ route('about') }}">Our Story</a>
                    <a class="mobile-link active" href="{{ route('operations') }}">Operations</a>
                    <a class="mobile-link" href="{{ route('home') }}#clients">Clients</a>
                    <a class="mobile-link" href="{{ route('home') }}#contact">Contact</a>
                </div>

                @if (Route::has('login'))
                    <div class="mobile-actions">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="btn btn-outline" style="display:block;text-align:center;">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-outline" style="display:block;text-align:center;">Login</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="btn btn-outline" style="display:block;text-align:center;">Register</a>
                            @endif
                        @endauth
                    </div>
                @endif
            </div>
        </div>
    </header>

    <div class="spacer-top"></div>


    <div class="ops-page">

        <!-- Hero -->
        <section class="ops-hero">
            <img
                src="https://images.unsplash.com/photo-1496247749665-49cf5b1022e9?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxpbmR1c3RyaWFsJTIwbWFjaGluZXJ5fGVufDF8fHx8MTc2MDYxODIzNnww&ixlib=rb-4.1.0&q=80&w=1800"
                alt="Operations"
            />
            <div class="overlay" aria-hidden="true"></div>
            <div class="content">
                <h1 class="reveal in">Our <span class="accent">Operations</span></h1>
                <p class="reveal in" style="transition-delay:.12s">
                    A comprehensive manufacturing process designed for efficiency and excellence
                </p>
            </div>
        </section>

        <!-- Process Overview -->
        <section class="ops-section-dark">
            <div class="container">
                <div class="center reveal">
                    <h2 class="section-title">Manufacturing <span class="accent">Process</span></h2>
                    <p class="section-subtitle">
                        Our streamlined manufacturing workflow ensures quality, efficiency, and timely delivery
                        at every stage of production.
                    </p>
                </div>

                <div class="ops-stack">
                    <!-- 1 Cutting -->
                    <div class="op-row reveal">
                        <div class="op-media">
                            <img class="op-img"
                                 src="https://images.unsplash.com/photo-1718184021018-d2158af6b321?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxmYWJyaWMlMjBjdXR0aW5nfGVufDF8fHx8MTc2MDY1Njg2OXww&ixlib=rb-4.1.0&q=80&w=1800"
                                 alt="Cutting">
                        </div>
                        <div class="op-text">
                            <div class="op-head">
                                <div class="op-icon" aria-hidden="true">
                                    <!-- Scissors -->
                                    <svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M4 4l8 8"></path>
                                        <path d="M4 12l8-8"></path>
                                        <path d="M14 14l6 6"></path>
                                        <path d="M14 10l6-6"></path>
                                        <circle cx="6" cy="18" r="2"></circle>
                                        <circle cx="6" cy="6" r="2"></circle>
                                    </svg>
                                </div>
                                <div>
                                    <div class="op-step">Step 1</div>
                                    <div class="op-title">Cutting</div>
                                </div>
                            </div>
                            <p class="op-desc">Precision cutting with advanced machinery to ensure accurate patterns and minimal waste.</p>
                            <p class="op-details">
                                Our state-of-the-art cutting equipment uses computer-aided design to achieve millimeter precision,
                                reducing material waste by up to 30% while maintaining the highest quality standards.
                            </p>
                        </div>
                    </div>

                    <!-- 2 Sewing (reverse) -->
                    <div class="op-row reverse reveal">
                        <div class="op-media">
                            <img class="op-img"
                                 src="https://images.unsplash.com/photo-1659707751291-3f8666211d07?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxzZXdpbmclMjBtYWNoaW5lJTIwaW5kdXN0cmlhbHxlbnwxfHx8fDE3NjA2NTY4Njl8MA&ixlib=rb-4.1.0&q=80&w=1800"
                                 alt="Sewing">
                        </div>
                        <div class="op-text">
                            <div class="op-head">
                                <div class="op-icon" aria-hidden="true">
                                    <!-- Shapes (simple) -->
                                    <svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M4 4h8v8H4z"></path>
                                        <path d="M14 10l6 2-2 6-6-2z"></path>
                                        <path d="M6 18h6"></path>
                                    </svg>
                                </div>
                                <div>
                                    <div class="op-step">Step 2</div>
                                    <div class="op-title">Sewing</div>
                                </div>
                            </div>
                            <p class="op-desc">Expert craftsmanship combined with modern techniques for superior quality and durability.</p>
                            <p class="op-details">
                                Our skilled artisans work alongside automated systems to deliver consistent, high-quality stitching.
                                Each piece undergoes rigorous quality checks to ensure durability and finish.
                            </p>
                        </div>
                    </div>

                    <!-- 3 Quality Control -->
                    <div class="op-row reveal">
                        <div class="op-media">
                            <img class="op-img"
                                 src="https://images.unsplash.com/photo-1599583863916-e06c29087f51?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxxdWFsaXR5JTIwY29udHJvbCUyMG1hbnVmYWN0dXJpbmd8ZW58MXx8fHwxNjU2ODY5fDA&ixlib=rb-4.1.0&q=80&w=1800"
                                 alt="Quality Control">
                        </div>
                        <div class="op-text">
                            <div class="op-head">
                                <div class="op-icon" aria-hidden="true">
                                    <!-- ClipboardCheck -->
                                    <svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M9 5H7a2 2 0 0 0-2 2v13a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/>
                                        <path d="M9 3h6v4H9z"/>
                                        <path d="M9 14l2 2 4-4"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="op-step">Step 3</div>
                                    <div class="op-title">Quality Control</div>
                                </div>
                            </div>
                            <p class="op-desc">Comprehensive inspection processes to maintain excellence in every product.</p>
                            <p class="op-details">
                                Multi-stage quality assurance including visual inspection, measurement verification,
                                and functional testing ensures that only the finest products reach our clients.
                            </p>
                        </div>
                    </div>

                    <!-- 4 Packaging (reverse) -->
                    <div class="op-row reverse reveal">
                        <div class="op-media">
                            <img class="op-img"
                                 src="https://images.unsplash.com/photo-1716193348750-9e22b67718c6?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHx3YXJlaG91c2UlMjBwYWNrYWdpbmd8ZW58MXx8fHwxNzYwNjU2ODY5fDA&ixlib=rb-4.1.0&q=80&w=1800"
                                 alt="Packaging">
                        </div>
                        <div class="op-text">
                            <div class="op-head">
                                <div class="op-icon" aria-hidden="true">
                                    <!-- Package -->
                                    <svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21 8l-9-5-9 5 9 5 9-5z"></path>
                                        <path d="M3 8v8l9 5 9-5V8"></path>
                                        <path d="M12 13v8"></path>
                                    </svg>
                                </div>
                                <div>
                                    <div class="op-step">Step 4</div>
                                    <div class="op-title">Packaging</div>
                                </div>
                            </div>
                            <p class="op-desc">Professional packaging solutions that protect products and enhance brand presentation.</p>
                            <p class="op-details">
                                Custom packaging designed to protect your products during transit while creating an unboxing
                                experience that reflects your brand's quality and values.
                            </p>
                        </div>
                    </div>

                    <!-- 5 Delivery -->
                    <div class="op-row reveal">
                        <div class="op-media">
                            <img class="op-img"
                                 src="https://images.unsplash.com/photo-1632914146475-bfe6fa6b2a12?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxmYWN0b3J5JTIwbWFudWZhY3R1cmluZ3xlbnwxfHx8fDE3NjA2MDExODZ8MA&ixlib=rb-4.1.0&q=80&w=1800"
                                 alt="Delivery">
                        </div>
                        <div class="op-text">
                            <div class="op-head">
                                <div class="op-icon" aria-hidden="true">
                                    <!-- Truck -->
                                    <svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M3 7h12v10H3z"></path>
                                        <path d="M15 10h4l2 2v5h-6z"></path>
                                        <circle cx="7" cy="17" r="2"></circle>
                                        <circle cx="18" cy="17" r="2"></circle>
                                    </svg>
                                </div>
                                <div>
                                    <div class="op-step">Step 5</div>
                                    <div class="op-title">Delivery</div>
                                </div>
                            </div>
                            <p class="op-desc">Reliable logistics and timely delivery to ensure your products reach you on schedule.</p>
                            <p class="op-details">
                                Partnering with trusted logistics providers, we offer tracked shipping, flexible delivery options,
                                and real-time updates to keep you informed every step of the way.
                            </p>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- Technology & Innovation -->
        <section class="ops-section">
            <div class="container">
                <div class="center reveal">
                    <h2 class="section-title">Technology & <span class="accent">Innovation</span></h2>
                    <p class="section-subtitle">Leveraging cutting-edge technology to deliver superior manufacturing solutions</p>
                </div>

                <div class="features">
                    <article class="feature reveal">
                        <div class="mini" aria-hidden="true">
                            <!-- Settings -->
                            <svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 15.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"/>
                                <path d="M19.4 15a7.8 7.8 0 0 0 .1-1l2-1.6-2-3.4-2.4.6a7.3 7.3 0 0 0-.8-.5L14.9 6h-3.8L9.7 9.1a7.3 7.3 0 0 0-.8.5L6.5 9l-2 3.4 2 1.6a7.8 7.8 0 0 0 0 2l-2 1.6 2 3.4 2.4-.6c.3.2.5.4.8.5L11.1 22h3.8l1.4-3.1c.3-.1.5-.3.8-.5l2.4.6 2-3.4-2-1.6z"/>
                            </svg>
                        </div>
                        <h3>Automated Systems</h3>
                        <p>Advanced automation for consistent quality and increased efficiency</p>
                    </article>

                    <article class="feature reveal">
                        <div class="mini" aria-hidden="true">
                            <!-- ClipboardCheck -->
                            <svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 5H7a2 2 0 0 0-2 2v13a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/>
                                <path d="M9 3h6v4H9z"/>
                                <path d="M9 14l2 2 4-4"/>
                            </svg>
                        </div>
                        <h3>Real-Time Tracking</h3>
                        <p>Monitor your production progress with our advanced tracking system</p>
                    </article>

                    <article class="feature reveal">
                        <div class="mini" aria-hidden="true">
                            <!-- Lightbulb -->
                            <svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 18h6"/>
                                <path d="M10 22h4"/>
                                <path d="M12 2a7 7 0 0 0-4 12c.6.5 1 1.2 1 2v1h6v-1c0-.8.4-1.5 1-2A7 7 0 0 0 12 2Z"/>
                            </svg>
                        </div>
                        <h3>Sustainable Practices</h3>
                        <p>Eco-friendly manufacturing processes that reduce environmental impact</p>
                    </article>
                </div>
            </div>
        </section>

    </div>

    <script>
        // Mobile menu
        const mobileBtn = document.getElementById("mobileMenuBtn");
        const mobilePanel = document.getElementById("mobilePanel");

        function setMobileOpen(open) {
            mobilePanel.classList.toggle("open", open);
            mobileBtn.setAttribute("aria-expanded", String(open));
            mobileBtn.innerHTML = open
                ? `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                     <path d="M18 6L6 18M6 6l12 12"></path>
                   </svg>`
                : `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                     <path d="M3 6h18M3 12h18M3 18h18"></path>
                   </svg>`;
        }

        if (mobileBtn && mobilePanel) {
            mobileBtn.addEventListener("click", () => setMobileOpen(!mobilePanel.classList.contains("open")));
            document.querySelectorAll(".mobile-panel a").forEach(a => a.addEventListener("click", () => setMobileOpen(false)));
        }

        // Scroll reveal
        const els = document.querySelectorAll(".reveal:not(.in)");
        const obs = new IntersectionObserver((entries) => {
            entries.forEach((e) => {
                if (e.isIntersecting) {
                    e.target.classList.add("in");
                    obs.unobserve(e.target);
                }
            });
        }, { threshold: 0.15 });
        els.forEach(el => obs.observe(el));
    </script>

</x-app-layout>
