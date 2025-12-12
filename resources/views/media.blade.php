<x-app-layout title="Media - ManufacTrack">

    {{-- Navbar (same pattern you use) --}}
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
                    <a class="mobile-link" href="{{ route('operations') }}">Operations</a>
                    <a class="mobile-link active" href="{{ route('media') }}">Media</a>
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

  

    @php
        $mediaImages = [
            ["src" => "https://images.unsplash.com/photo-1632914146475-bfe6fa6b2a12?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxmYWN0b3J5JTIwbWFudWZhY3R1cmluZ3xlbnwxfHx8fDE3NjA2MDExODZ8MA&ixlib=rb-4.1.0&q=80&w=1800", "alt" => "Factory manufacturing facility", "category" => "Facility"],
            ["src" => "https://images.unsplash.com/photo-1476683874822-744764a2438f?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHx0ZXh0aWxlJTIwcHJvZHVjdGlvbnxlbnwxfHx8fDE3NjA2NTY4Njd8MA&ixlib=rb-4.1.0&q=80&w=1800", "alt" => "Textile production", "category" => "Production"],
            ["src" => "https://images.unsplash.com/photo-1496247749665-49cf5b1022e9?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHxpbmR1c3RyaWFsJTIwbWFjaGluZXJ5fGVufDF8fHx8MTc2MDYxODIzNnww&ixlib=rb-4.1.0&q=80&w=1800", "alt" => "Industrial machinery", "category" => "Equipment"],
            ["src" => "https://images.unsplash.com/photo-1758269664127-1f744a56e06c?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxnYXJtZW50JTIwcHJvZHVjdGlvbnxlbnwxfHx8fDE3NjA2NTY2ODF8MA&ixlib=rb-4.1.0&q=80&w=1800", "alt" => "Garment production", "category" => "Production"],
            ["src" => "https://images.unsplash.com/photo-1721578006568-17901600cff3?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHxjbG90aGluZyUyMG1hbnVmYWN0dXJpbmd8ZW58MXx8fHwxNzYwNjU2ODY4fDA&ixlib=rb-4.1.0&q=80&w=1800", "alt" => "Clothing manufacturing", "category" => "Production"],
            ["src" => "https://images.unsplash.com/photo-1659707751291-3f8666211d07?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxzZXdpbmclMjBtYWNoaW5lJTIwaW5kdXN0cmlhbHxlbnwxfHx8fDE3NjA2NTY4Njl8MA&ixlib=rb-4.1.0&q=80&w=1800", "alt" => "Industrial sewing machine", "category" => "Equipment"],
            ["src" => "https://images.unsplash.com/photo-1718184021018-d2158af6b321?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHxmYWJyaWMlMjBjdXR0aW5nfGVufDF8fHx8MTc2MDY1Njg2OXww&ixlib=rb-4.1.0&q=80&w=1800", "alt" => "Fabric cutting process", "category" => "Process"],
            ["src" => "https://images.unsplash.com/photo-1716193348750-9e22b67718c6?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHx3YXJlaG91c2UlMjBwYWNrYWdpbmd8ZW58MXx8fHwxNzYwNjU2ODY5fDA&ixlib=rb-4.1.0&q=80&w=1800", "alt" => "Warehouse packaging", "category" => "Facility"],
            ["src" => "https://images.unsplash.com/photo-1599583863916-e06c29087f51?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHxxdWFsaXR5JTIwY29udHJvbCUyMG1hbnVmYWN0dXJpbmd8ZW58MXx8fHwxNjU2ODY5fDA&ixlib=rb-4.1.0&q=80&w=1800", "alt" => "Quality control", "category" => "Process"],
            ["src" => "https://images.unsplash.com/photo-1748348209623-906c42dd1f7b?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxtYW51ZmFjdHVyaW5nJTIwdGVhbXxlbnwxfHx8fDE3NjA2NTY2ODF8MA&ixlib=rb-4.1.0&q=80&w=1800", "alt" => "Manufacturing team", "category" => "Team"],
            ["src" => "https://images.unsplash.com/photo-1581092918484-8313e1f7e8d8?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&q=80&w=1400", "alt" => "Manufacturing process", "category" => "Process"],
            ["src" => "https://images.unsplash.com/photo-1647427060118-4911c9821b82?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHxpbmR1c3RyaWFsJTIwbWFudWZhY3R1cmluZ3xlbnwxfHx8fDE3NjA2MzU1NzB8MA&ixlib=rb-4.1.0&q=80&w=1800", "alt" => "Industrial manufacturing", "category" => "Facility"],
        ];

        $categories = ["All","Facility","Production","Equipment","Process","Team"];
    @endphp

    <div class="media-page">

        <!-- Hero -->
        <section class="media-hero">
            <img
                src="https://images.unsplash.com/photo-1632914146475-bfe6fa6b2a12?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHxmYWN0b3J5JTIwbWFudWZhY3R1cmluZ3xlbnwxfHx8fDE3NjA2MDExODZ8MA&ixlib=rb-4.1.0&q=80&w=1800"
                alt="Media Gallery"
            />
            <div class="overlay" aria-hidden="true"></div>
            <div class="content">
                <h1 class="reveal in">Media <span class="accent">Gallery</span></h1>
                <p class="reveal in" style="transition-delay:.12s">Explore our state-of-the-art facilities and manufacturing processes</p>
            </div>
        </section>

        <!-- Filters -->
        <section class="section-dark">
            <div class="container">
                <div class="filters reveal">
                    @foreach ($categories as $cat)
                        <button
                            type="button"
                            class="filter-btn {{ $cat === 'All' ? 'active' : '' }}"
                            data-filter="{{ $cat }}"
                        >
                            {{ $cat }}
                        </button>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- Grid -->
        <section class="gallery">
            <div class="container">
                <div class="grid" id="galleryGrid">
                    @foreach ($mediaImages as $i => $img)
                        <div
                            class="tile reveal"
                            data-category="{{ $img['category'] }}"
                            data-src="{{ $img['src'] }}"
                            data-alt="{{ $img['alt'] }}"
                            data-cat="{{ $img['category'] }}"
                            tabindex="0"
                            role="button"
                            aria-label="View image: {{ $img['alt'] }}"
                        >
                            <img src="{{ $img['src'] }}" alt="{{ $img['alt'] }}">
                            <div class="hover-overlay" aria-hidden="true"><span>View Image</span></div>
                            <div class="caption" aria-hidden="true">
                                <p class="cap-title">{{ $img['alt'] }}</p>
                                <p class="cap-cat">{{ $img['category'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- Lightbox -->
        <div class="lightbox" id="lightbox" aria-hidden="true">
            <button class="close-btn" id="lightboxClose" aria-label="Close">
                <!-- X icon -->
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                    <path d="M18 6L6 18M6 6l12 12"></path>
                </svg>
            </button>

            <div class="lightbox-inner" id="lightboxInner" role="dialog" aria-modal="true">
                <img class="lightbox-img" id="lightboxImg" src="" alt="">
                <div class="lightbox-meta">
                    <p class="t" id="lightboxTitle"></p>
                    <p class="c" id="lightboxCat"></p>
                </div>
            </div>
        </div>

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

        // Filters
        const buttons = Array.from(document.querySelectorAll(".filter-btn"));
        const tiles = Array.from(document.querySelectorAll(".tile"));
        buttons.forEach(btn => {
            btn.addEventListener("click", () => {
                const filter = btn.dataset.filter;

                buttons.forEach(b => b.classList.toggle("active", b === btn));

                tiles.forEach(tile => {
                    const cat = tile.dataset.category;
                    const show = (filter === "All" || cat === filter);
                    tile.style.display = show ? "" : "none";
                });
            });
        });

        // Lightbox
        const lightbox = document.getElementById("lightbox");
        const lbImg = document.getElementById("lightboxImg");
        const lbTitle = document.getElementById("lightboxTitle");
        const lbCat = document.getElementById("lightboxCat");
        const lbClose = document.getElementById("lightboxClose");
        const lbInner = document.getElementById("lightboxInner");

        function openLightbox(src, alt, cat) {
            lbImg.src = src;
            lbImg.alt = alt;
            lbTitle.textContent = alt;
            lbCat.textContent = cat;
            lightbox.classList.add("open");
            lightbox.setAttribute("aria-hidden", "false");
            document.body.style.overflow = "hidden";
        }

        function closeLightbox() {
            lightbox.classList.remove("open");
            lightbox.setAttribute("aria-hidden", "true");
            lbImg.src = "";
            document.body.style.overflow = "";
        }

        tiles.forEach(tile => {
            tile.addEventListener("click", () => openLightbox(tile.dataset.src, tile.dataset.alt, tile.dataset.cat));
            tile.addEventListener("keydown", (e) => {
                if (e.key === "Enter" || e.key === " ") {
                    e.preventDefault();
                    openLightbox(tile.dataset.src, tile.dataset.alt, tile.dataset.cat);
                }
            });
        });

        lbClose.addEventListener("click", (e) => {
            e.stopPropagation();
            closeLightbox();
        });

        lightbox.addEventListener("click", () => closeLightbox());
        lbInner.addEventListener("click", (e) => e.stopPropagation());

        window.addEventListener("keydown", (e) => {
            if (e.key === "Escape" && lightbox.classList.contains("open")) closeLightbox();
        });

        // Reveal
        const els = document.querySelectorAll(".reveal:not(.in)");
        const obs = new IntersectionObserver((entries) => {
            entries.forEach((e) => {
                if (e.isIntersecting) {
                    e.target.classList.add("in");
                    obs.unobserve(e.target);
                }
            });
        }, { threshold: 0.12 });
        els.forEach(el => obs.observe(el));
    </script>

</x-app-layout>
