<x-app-layout title="Operations - AAA Studios">

    {{-- DARK WRAPPER --}}
    <div class="site-dark">

        {{-- NAVBAR --}}
        <header class="nav">
            <div class="container">
                <div class="nav-row">

                    <a class="brand" href="{{ route('home') }}">
                        <div class="brand-badge"><span>AAA</span></div>
                        <span>AAA Studios</span>
                    </a>

                    <nav class="nav-links">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a>
                        <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">Our Story</a>
                        <a class="nav-link {{ request()->routeIs('operations') ? 'active' : '' }}" href="{{ route('operations') }}">Operations</a>
                        <a class="nav-link {{ request()->routeIs('media') ? 'active' : '' }}" href="{{ route('media') }}">Media</a>
                        <a class="nav-link" href="{{ route('home') }}#contact">Contact</a>
                    </nav>

                    <div class="nav-actions">
                        @auth
                            <a href="{{ route('dashboard') }}" class="btn btn-outline">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-outline">Login</a>
                            <a href="{{ route('register') }}" class="btn btn-outline">Register</a>
                        @endauth

                        <button class="icon-btn menu-btn" id="mobileMenuBtn">
                            <svg viewBox="0 0 24 24" stroke-width="2">
                                <path d="M3 6h18M3 12h18M3 18h18"></path>
                            </svg>
                        </button>
                    </div>

                </div>

                {{-- MOBILE --}}
                <div class="mobile-panel" id="mobilePanel">
                    <div class="mobile-links">
                        <a class="mobile-link" href="{{ route('home') }}">Home</a>
                        <a class="mobile-link" href="{{ route('about') }}">Our Story</a>
                        <a class="mobile-link active" href="{{ route('operations') }}">Operations</a>
                        <a class="mobile-link" href="{{ route('media') }}">Media</a>
                        <a class="mobile-link" href="{{ route('home') }}#contact">Contact</a>
                    </div>
                </div>

            </div>
        </header>

        <div class="spacer-top"></div>

        {{-- PAGE --}}
        <main class="ops-page">

            {{-- HERO --}}
            <section class="ops-hero">
                <img src="https://images.unsplash.com/photo-1496247749665-49cf5b1022e9?w=1800" alt="">
                <div class="overlay"></div>
                <div class="content">
                    <h1 class="reveal in">Our <span class="accent">Operations</span></h1>
                    <p class="reveal in" style="transition-delay:.12s">
                        Precision manufacturing built for scale and quality
                    </p>
                </div>
            </section>

            {{-- PROCESS --}}
            <section class="ops-section-dark">
                <div class="container">

                    <div class="center reveal">
                        <h2 class="section-title">Manufacturing <span class="accent">Process</span></h2>
                        <p class="section-subtitle">
                            Every step is engineered for accuracy, efficiency, and consistency
                        </p>
                    </div>

                    <div class="ops-stack">

                        {{-- STEP --}}
                        <div class="op-row reveal">
                            <div class="op-media">
                                <img class="op-img" src="https://images.unsplash.com/photo-1718184021018-d2158af6b321?w=1800">
                            </div>
                            <div class="op-text">
                                <div class="op-head">
                                    <div class="op-icon">
                                        <svg viewBox="0 0 24 24"><path d="M4 4l8 8M4 12l8-8"/></svg>
                                    </div>
                                    <div>
                                        <div class="op-step">Step 01</div>
                                        <div class="op-title">Cutting</div>
                                    </div>
                                </div>
                                <p class="op-desc">High-precision CNC and laser cutting systems</p>
                                <p class="op-details">
                                    Automated cutting ensures zero tolerance errors and reduced material waste.
                                </p>
                            </div>
                        </div>

                        {{-- STEP --}}
                        <div class="op-row reverse reveal">
                            <div class="op-media">
                                <img class="op-img" src="https://images.unsplash.com/photo-1659707751291-3f8666211d07?w=1800">
                            </div>
                            <div class="op-text">
                                <div class="op-head">
                                    <div class="op-icon">
                                        <svg viewBox="0 0 24 24"><path d="M4 4h8v8"/></svg>
                                    </div>
                                    <div>
                                        <div class="op-step">Step 02</div>
                                        <div class="op-title">Assembly</div>
                                    </div>
                                </div>
                                <p class="op-desc">Human expertise enhanced by automation</p>
                                <p class="op-details">
                                    Skilled technicians assemble components with real-time quality monitoring.
                                </p>
                            </div>
                        </div>

                        {{-- STEP --}}
                        <div class="op-row reveal">
                            <div class="op-media">
                                <img class="op-img" src="https://images.unsplash.com/photo-1599583863916-e06c29087f51?w=1800">
                            </div>
                            <div class="op-text">
                                <div class="op-head">
                                    <div class="op-icon">
                                        <svg viewBox="0 0 24 24"><path d="M9 14l2 2 4-4"/></svg>
                                    </div>
                                    <div>
                                        <div class="op-step">Step 03</div>
                                        <div class="op-title">Quality Control</div>
                                    </div>
                                </div>
                                <p class="op-desc">Multi-stage inspection protocols</p>
                                <p class="op-details">
                                    Visual, mechanical, and performance testing before approval.
                                </p>
                            </div>
                        </div>

                        {{-- STEP --}}
                        <div class="op-row reverse reveal">
                            <div class="op-media">
                                <img class="op-img" src="https://images.unsplash.com/photo-1716193348750-9e22b67718c6?w=1800">
                            </div>
                            <div class="op-text">
                                <div class="op-head">
                                    <div class="op-icon">
                                        <svg viewBox="0 0 24 24"><path d="M21 8l-9-5-9 5"/></svg>
                                    </div>
                                    <div>
                                        <div class="op-step">Step 04</div>
                                        <div class="op-title">Packaging</div>
                                    </div>
                                </div>
                                <p class="op-desc">Secure and branded packaging</p>
                                <p class="op-details">
                                    Designed for protection, logistics efficiency, and brand presence.
                                </p>
                            </div>
                        </div>

                    </div>
                </div>
            </section>

            {{-- FEATURES --}}
            <section class="ops-section">
                <div class="container">
                    <div class="center reveal">
                        <h2 class="section-title">Technology & <span class="accent">Innovation</span></h2>
                        <p class="section-subtitle">Modern systems powering smart manufacturing</p>
                    </div>

                    <div class="features">
                        <article class="feature reveal">
                            <h3>Automation</h3>
                            <p>AI-driven production lines with real-time optimization.</p>
                        </article>
                        <article class="feature reveal">
                            <h3>Tracking</h3>
                            <p>Full visibility from raw materials to delivery.</p>
                        </article>
                        <article class="feature reveal">
                            <h3>Sustainability</h3>
                            <p>Eco-efficient processes and waste reduction.</p>
                        </article>
                    </div>
                </div>
            </section>

        </main>
    </div>

    {{-- JS --}}
    <script>
        const btn = document.getElementById("mobileMenuBtn");
        const panel = document.getElementById("mobilePanel");
        btn?.addEventListener("click", () => panel.classList.toggle("open"));

        const obs = new IntersectionObserver(es=>{
            es.forEach(e=>{
                if(e.isIntersecting){
                    e.target.classList.add("in");
                    obs.unobserve(e.target);
                }
            })
        },{threshold:.15});
        document.querySelectorAll(".reveal").forEach(el=>obs.observe(el));
    </script>

</x-app-layout>
