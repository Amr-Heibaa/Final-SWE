<x-app-layout title="About - ManufacTrack">

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

    <div class="about-page">
        <!-- Hero Banner -->
        <section class="about-hero">
            <img
                src="https://images.unsplash.com/photo-1748348209623-906c42dd1f7b?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxtYW51ZmFjdHVyaW5nJTIwdGVhbXxlbnwxfHx8fDE3NjA2NTY2ODF8MA&ixlib=rb-4.1.0&q=80&w=1800"
                alt="Our Story"
            />
            <div class="overlay" aria-hidden="true"></div>
            <div class="content">
                <h1 class="reveal in">Our <span class="brand">Story</span></h1>
            </div>
        </section>

        <!-- Who We Are + Cards + Journey -->
        <section class="about-section">
            <div class="about-container">

                <!-- Who We Are -->
                <div class="grid-2">
                    <div class="reveal reveal-left">
                        <img
                            class="img-card"
                            src="https://images.unsplash.com/photo-1748348209623-906c42dd1f7b?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxtYW51ZmFjdHVyaW5nJTIwdGVhbXxlbnwxfHx8fDE3NjA2NTY2ODF8MA&ixlib=rb-4.1.0&q=80&w=1800"
                            alt="Manufacturing facility"
                        />
                    </div>

                    <div class="reveal reveal-right">
                        <div class="who-title">
                            <div class="icon-badge" aria-hidden="true">
                                <!-- Users icon -->
                                <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                    <circle cx="9" cy="7" r="4"/>
                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                                </svg>
                            </div>
                            <h2 class="h2">Who We Are</h2>
                        </div>

                        <p class="p">
                            ManufacTrack is a leading manufacturing solutions provider dedicated to delivering
                            exceptional quality and precision in every project. With decades of combined experience,
                            we bring innovation and expertise to the manufacturing industry.
                        </p>
                        <p class="p">
                            Our team consists of skilled professionals who are passionate about manufacturing excellence.
                            We leverage cutting-edge technology and traditional craftsmanship to deliver superior results
                            that exceed our clients' expectations.
                        </p>
                    </div>
                </div>

                <!-- Mission, Strategy, Values -->
                <div class="cards">
                    <article class="card reveal">
                        <div class="icon-badge" aria-hidden="true">
                            <!-- Target icon -->
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"/>
                                <circle cx="12" cy="12" r="6"/>
                                <circle cx="12" cy="12" r="2"/>
                            </svg>
                        </div>
                        <h3>Our Mission</h3>
                        <p>
                            To revolutionize the manufacturing process through cutting-edge technology,
                            sustainable practices, and unwavering commitment to quality. We strive to exceed
                            client expectations while maintaining the highest standards of excellence.
                        </p>
                    </article>

                    <article class="card reveal">
                        <div class="icon-badge" aria-hidden="true">
                            <!-- Lightbulb icon -->
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 18h6"/>
                                <path d="M10 22h4"/>
                                <path d="M12 2a7 7 0 0 0-4 12c.6.5 1 1.2 1 2v1h6v-1c0-.8.4-1.5 1-2A7 7 0 0 0 12 2Z"/>
                            </svg>
                        </div>
                        <h3>Our Strategy</h3>
                        <p>
                            We combine traditional craftsmanship with modern technology to create efficient,
                            scalable, and sustainable manufacturing solutions. Our approach focuses on continuous
                            improvement, innovation, and building lasting partnerships with our clients.
                        </p>
                    </article>

                    <article class="card reveal">
                        <div class="icon-badge" aria-hidden="true">
                            <!-- Award icon -->
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="8" r="6"/>
                                <path d="M15.5 14.5 18 22l-6-3-6 3 2.5-7.5"/>
                            </svg>
                        </div>
                        <h3>Our Values</h3>
                        <p>
                            Quality, integrity, innovation, and customer satisfaction are at the core of everything
                            we do. We believe in building long-term relationships based on trust, transparency,
                            and delivering exceptional value to our clients.
                        </p>
                    </article>
                </div>

                <!-- Journey -->
                <div class="reveal">
                    <h2 class="journey-title">Our <span class="brand">Journey</span></h2>

                    <div class="timeline">
                        <div class="milestone reveal reveal-left">
                            <div class="year">2010</div>
                            <div>
                                <h3>Foundation</h3>
                                <p>ManufacTrack was founded with a vision to transform manufacturing processes.</p>
                            </div>
                        </div>

                        <div class="milestone reveal reveal-left">
                            <div class="year">2015</div>
                            <div>
                                <h3>Expansion</h3>
                                <p>Expanded operations and introduced advanced tracking technology.</p>
                            </div>
                        </div>

                        <div class="milestone reveal reveal-left">
                            <div class="year">2020</div>
                            <div>
                                <h3>Innovation</h3>
                                <p>Launched AI-powered quality control and sustainable manufacturing initiatives.</p>
                            </div>
                        </div>

                        <div class="milestone reveal reveal-left">
                            <div class="year">2025</div>
                            <div>
                                <h3>Industry Leader</h3>
                                <p>Recognized as a leading manufacturing solutions provider worldwide.</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>
    </div>

    <script>
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
