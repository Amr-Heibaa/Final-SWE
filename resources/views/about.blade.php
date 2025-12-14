<x-app-layout title="About - ManufacTrack">

<div class="site-dark about-page">

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
                    <a class="nav-link active" href="{{ route('about') }}">Our Story</a>
                    <a class="nav-link" href="{{ route('operations') }}">Operations</a>
                    <a class="nav-link" href="{{ route('media') }}">Media</a>
                    <a class="nav-link" href="{{ route('meetings.create') }}">Contact</a>
                </nav>

                <div class="nav-actions">
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn btn-outline">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline">Login</a>
                    @endauth
                </div>

            </div>
        </div>
    </header>


    {{-- HERO --}}
    <section class="about-hero">
        <img src="https://images.unsplash.com/photo-1748348209623-906c42dd1f7b?auto=format&fit=crop&w=1800&q=80" alt="">
        <div class="overlay"></div>
        <div class="content">
            <h1 class="reveal in">Our <span class="brand">Story</span></h1>
        </div>
    </section>

    {{-- CONTENT --}}
    <section class="about-section">
        <div class="about-container">

            {{-- WHO WE ARE --}}
            <div class="grid-2">
                <img class="img-card reveal reveal-left"
                     src="https://images.unsplash.com/photo-1632914146475-bfe6fa6b2a12?auto=format&fit=crop&w=1400&q=80">

                <div class="reveal reveal-right">
                    <div class="who-title">
                        <div class="icon-badge">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2">
                                <circle cx="12" cy="7" r="4"/>
                                <path d="M5.5 21a6.5 6.5 0 0 1 13 0"/>
                            </svg>
                        </div>
                        <h2 class="h2">Who We Are</h2>
                    </div>

                    <p class="p">
                        ManufacTrack is a manufacturing solutions provider focused on precision,
                        transparency, and operational excellence.
                    </p>
                    <p class="p">
                        We blend modern technology with hands-on expertise to deliver reliable,
                        scalable production solutions.
                    </p>
                </div>
            </div>

            {{-- CARDS --}}
            <div class="cards">
                <div class="card reveal">
                    <h3>Our Mission</h3>
                    <p>
                        To transform manufacturing workflows through technology,
                        quality control, and accountability.
                    </p>
                </div>

                <div class="card reveal">
                    <h3>Our Strategy</h3>
                    <p>
                        Combine data-driven decisions with real-world manufacturing experience.
                    </p>
                </div>

                <div class="card reveal">
                    <h3>Our Values</h3>
                    <p>
                        Integrity, quality, transparency, and long-term partnerships.
                    </p>
                </div>
            </div>

            {{-- JOURNEY --}}
            <h2 class="journey-title reveal"><span class="brand"> Our Journey</span></h2>

            <div class="timeline">
                <div class="milestone reveal reveal-left">
                    <div class="year">2010</div>
                    <div>
                        <h3>Foundation</h3>
                        <p>Company established with a vision for smarter manufacturing.</p>
                    </div>
                </div>

                <div class="milestone reveal reveal-left">
                    <div class="year">2018</div>
                    <div>
                        <h3>Growth</h3>
                        <p>Expanded operations and digital tracking systems.</p>
                    </div>
                </div>

                <div class="milestone reveal reveal-left">
                    <div class="year">2025</div>
                    <div>
                        <h3>Leadership</h3>
                        <p>Recognized as a trusted industry partner.</p>
                    </div>
                </div>
            </div>

        </div>
    </section>

</div>

{{-- REVEAL SCRIPT --}}
<script>
const els = document.querySelectorAll(".reveal:not(.in)");
const obs = new IntersectionObserver(entries => {
  entries.forEach(e => {
    if (e.isIntersecting) {
      e.target.classList.add("in");
      obs.unobserve(e.target);
    }
  });
}, { threshold: 0.15 });
els.forEach(el => obs.observe(el));
</script>

</x-app-layout>
