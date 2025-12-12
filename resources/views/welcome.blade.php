<x-app-layout title="ManufacTrack">

    <!-- NAVBAR (Your custom navbar) -->
    <header class="nav">
        <div class="container">
            <div class="nav-row">
                <a class="brand" href="{{ url('/') }}">
                    <div class="brand-badge" aria-hidden="true"><span>M</span></div>
                    <span>ManufacTrack</span>
                </a>

                <nav class="nav-links" aria-label="Primary">
                    <a class="nav-link" href="{{ route('home') }}">Home</a>
                    <a class="nav-link" href="{{ route('about') }}">Our Story</a>
                    <a class="nav-link" href="{{ route('operations') }}">Operations</a>
                    <a class="nav-link" href="{{ route('media') }}">Media</a>
                    <a class="nav-link" href="#contact">Contact</a>
                </nav>

                <div class="nav-actions">
                    <!-- Auth Buttons -->
                    @if (Route::has('login'))
                    <div class="auth-actions">
                        @auth
                        <a href="{{ url('/dashboard') }}" class="btn btn-outline">Dashboard</a>
                        @else
                        <a href="{{ route('login') }}" class="btn btn-outline">Login</a>

                        @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn btn-outline">register</a>
                        @endif
                        @endauth
                    </div>
                    @endif

                    <!-- Mobile menu button -->
                    <button
                        class="icon-btn menu-btn"
                        id="mobileMenuBtn"
                        aria-label="Toggle menu"
                        aria-expanded="false"
                        type="button">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                            <path d="M3 6h18M3 12h18M3 18h18"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile Panel -->
            <div class="mobile-panel" id="mobilePanel">
                <div class="mobile-links">
                    <a class="mobile-link" href="#home">Home</a>
                    <a class="mobile-link" href="#about">Our Story</a>
                    <a class="mobile-link" href="#operations">Operations</a>
                    <a class="mobile-link" href="#clients">Clients</a>
                    <a class="mobile-link" href="#contact">Contact</a>
                </div>

                @if (Route::has('login'))
                <div class="mobile-actions">
                    @auth
                    <a href="{{ url('/dashboard') }}" class="btn btn-outline" style="display:block;text-align:center;">
                        Dashboard
                    </a>
                    @else
                    <a href="{{ route('login') }}" class="btn btn-outline" style="display:block;text-align:center;">
                        Login
                    </a>

                    @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn btn-outline" style="display:block;text-align:center;">
                        register
                    </a>
                    @endif
                    @endauth
                </div>
                @endif
            </div>
        </div>
    </header>

    <!-- Spacer for fixed navbar -->
    <div class="spacer-top"></div>

    <!-- HERO -->
    <section class="hero" id="home">
        <div class="container">
            <div class="hero-inner">
                <h1>Tracking Your Manufacturing Journey</h1>
                <p>Precision, quality, and excellence in every step of production</p>

                <div class="scroll-indicator" id="scrollDownBtn" role="button" tabindex="0" aria-label="Scroll down to about section">
                    <span>Scroll Down</span>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 9l6 6 6-6"></path>
                    </svg>
                </div>
            </div>
        </div>
    </section>

    <!-- OUR STORY -->
    <section id="about" class="bg-1">
        <div class="container">
            <h2 class="title">Our <span class="accent">Story</span></h2>

            <div class="grid-2">
                <img
                    class="card-img"
                    src="https://images.unsplash.com/photo-1748348209623-906c42dd1f7b?auto=format&fit=crop&w=1400&q=80"
                    alt="Our team" />

                <div>
                    <div class="story-block">
                        <h3>Who We Are</h3>
                        <p>
                            ManufacTrack is a leading manufacturing solutions provider dedicated to delivering
                            exceptional quality and precision in every project. With decades of combined experience,
                            we bring innovation and expertise to the manufacturing industry.
                        </p>
                    </div>

                    <div class="story-block">
                        <h3>Our Mission</h3>
                        <p>
                            To revolutionize the manufacturing process through cutting-edge technology,
                            sustainable practices, and unwavering commitment to quality.
                        </p>
                    </div>

                    <div class="story-block">
                        <h3>Our Strategy</h3>
                        <p>
                            We combine traditional craftsmanship with modern technology to create efficient,
                            scalable, and sustainable manufacturing solutions.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- OPERATIONS -->
    <section id="operations" class="bg-0">
        <div class="container">
            <h2 class="title">Our <span class="accent">Operations</span></h2>
            <p class="subtitle">A streamlined manufacturing process designed for efficiency and excellence</p>

            <div class="ops-grid">
                <article class="ops-card">
                    <div class="ops-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 4l8 8"></path>
                            <path d="M4 12l8-8"></path>
                            <path d="M14 14l6 6"></path>
                            <path d="M14 10l6-6"></path>
                            <circle cx="6" cy="18" r="2"></circle>
                            <circle cx="6" cy="6" r="2"></circle>
                        </svg>
                    </div>
                    <h3>Cutting</h3>
                    <p>Precision cutting with advanced machinery to ensure accurate patterns and minimal waste.</p>
                </article>

                <article class="ops-card">
                    <div class="ops-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 4h8v8H4z"></path>
                            <path d="M14 10l6 2-2 6-6-2z"></path>
                            <path d="M6 18h6"></path>
                        </svg>
                    </div>
                    <h3>Sewing</h3>
                    <p>Expert craftsmanship combined with modern techniques for superior quality and durability.</p>
                </article>

                <article class="ops-card">
                    <div class="ops-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 8l-9-5-9 5 9 5 9-5z"></path>
                            <path d="M3 8v8l9 5 9-5V8"></path>
                            <path d="M12 13v8"></path>
                        </svg>
                    </div>
                    <h3>Packaging</h3>
                    <p>Professional packaging solutions that protect products and enhance brand presentation.</p>
                </article>

                <article class="ops-card">
                    <div class="ops-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 7h12v10H3z"></path>
                            <path d="M15 10h4l2 2v5h-6z"></path>
                            <circle cx="7" cy="17" r="2"></circle>
                            <circle cx="18" cy="17" r="2"></circle>
                        </svg>
                    </div>
                    <h3>Delivery</h3>
                    <p>Reliable logistics and timely delivery to ensure your products reach you on schedule.</p>
                </article>
            </div>
        </div>
    </section>

    <!-- CLIENTS -->
    <section id="clients" class="bg-1">
        <div class="container">
            <h2 class="title">Our <span class="accent">Clients</span></h2>
            <p class="subtitle">Trusted by leading brands worldwide</p>

            <div class="clients-grid">
                <div class="client-tile">
                    <div class="client-logo">BA</div>
                </div>
                <div class="client-tile">
                    <div class="client-logo">BB</div>
                </div>
                <div class="client-tile">
                    <div class="client-logo">BC</div>
                </div>
                <div class="client-tile">
                    <div class="client-logo">BD</div>
                </div>
                <div class="client-tile">
                    <div class="client-logo">BE</div>
                </div>
                <div class="client-tile">
                    <div class="client-logo">BF</div>
                </div>
            </div>
        </div>
    </section>



    <script>
        // ----- Mobile menu toggle -----
        const mobileBtn = document.getElementById("mobileMenuBtn");
        const mobilePanel = document.getElementById("mobilePanel");

        function setMobileOpen(open) {
            mobilePanel.classList.toggle("open", open);
            mobileBtn.setAttribute("aria-expanded", String(open));
            mobileBtn.innerHTML = open ?
                `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                     <path d="M18 6L6 18M6 6l12 12"></path>
                   </svg>` :
                `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                     <path d="M3 6h18M3 12h18M3 18h18"></path>
                   </svg>`;
        }

        if (mobileBtn && mobilePanel) {
            mobileBtn.addEventListener("click", () => {
                setMobileOpen(!mobilePanel.classList.contains("open"));
            });

            document.querySelectorAll(".mobile-panel a").forEach(a => {
                a.addEventListener("click", () => setMobileOpen(false));
            });
        }

        // ----- Scroll down behavior -----
        const scrollDownBtn = document.getElementById("scrollDownBtn");

        function scrollToAbout() {
            const about = document.getElementById("about");
            if (about) about.scrollIntoView({
                behavior: "smooth"
            });
        }

        if (scrollDownBtn) {
            scrollDownBtn.addEventListener("click", (e) => {
                e.preventDefault();
                scrollToAbout();
            });
            scrollDownBtn.addEventListener("keydown", (e) => {
                if (e.key === "Enter" || e.key === " ") {
                    e.preventDefault();
                    scrollToAbout();
                }
            });
        }

        // Active link highlight based on scroll position (simple)
        const sections = ["home", "about", "operations", "clients", "contact"].map(id => document.getElementById(id)).filter(Boolean);
        const navLinks = Array.from(document.querySelectorAll(".nav-link"));

        function setActive(id) {
            navLinks.forEach(a => a.classList.toggle("active", a.getAttribute("href") === `#${id}`));
        }

        const secObs = new IntersectionObserver((entries) => {
            entries.forEach(e => {
                if (e.isIntersecting) setActive(e.target.id);
            });
        }, {
            threshold: 0.35
        });

        sections.forEach(s => secObs.observe(s));
        setActive("home");
    </script>

</x-app-layout>