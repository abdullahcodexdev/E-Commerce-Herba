<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Herbal Roots — Pure & Natural Herbal Products')</title>
    <meta name="description" content="@yield('meta', 'Premium organic herbal supplements, oils and remedies — pure, natural and ethically sourced.')">
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/favicon.svg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/site.css') }}">
    <script>window.CSRF = '{{ csrf_token() }}';</script>
</head>
<body>
    <div class="topbar">🌿 Free shipping on orders over <span>Rs. 5,000</span> &nbsp;•&nbsp; 100% Organic &amp; Lab-Tested</div>

    <header class="site">
        <div class="container nav">
            <a href="{{ route('home') }}" class="logo"><img src="{{ asset('images/logo.svg') }}" alt="Herbal Roots"></a>
            <button class="menu-toggle" aria-label="Menu">☰</button>
            <ul class="nav-links">
                <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a></li>
                <li><a href="{{ route('shop.index') }}" class="{{ request()->routeIs('shop.*') ? 'active' : '' }}">Shop</a></li>
                <li><a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">About</a></li>
                <li><a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}">Contact</a></li>
                @auth @if(auth()->user()->is_admin)
                    <li><a href="{{ route('admin.orders.index') }}" class="{{ request()->routeIs('admin.*') ? 'active' : '' }}" style="color:var(--gold-dark);font-weight:700">⚙ Admin</a></li>
                @endif @endauth
            </ul>
            <div class="nav-actions">
                @auth
                    <div class="dropdown" style="position:relative">
                        <a href="{{ route('orders.index') }}" class="icon-btn" title="My account">👤</a>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="icon-btn" title="Login">👤</a>
                @endauth
                <button class="icon-btn" data-open-cart title="Cart">
                    🛒
                    <span class="cart-badge" style="{{ $cartCount > 0 ? '' : 'display:none' }}">{{ $cartCount }}</span>
                </button>
            </div>
        </div>
    </header>

    @if (session('success'))<div class="container" style="margin-top:1rem"><div class="alert alert-success">{{ session('success') }}</div></div>@endif
    @if (session('error'))<div class="container" style="margin-top:1rem"><div class="alert alert-error">{{ session('error') }}</div></div>@endif

    <main>@yield('content')</main>

    <footer class="site">
        <div class="container">
            <div class="foot-grid">
                <div>
                    <div class="foot-logo"><img src="{{ asset('images/logo-light.svg') }}" alt="Herbal Roots"></div>
                    <p style="font-size:.92rem;max-width:300px">Nature's purest remedies, ethically sourced and lab-tested for your everyday wellness journey.</p>
                    <div class="socials">
                        <a href="#" aria-label="Facebook">f</a><a href="#" aria-label="Instagram">◎</a>
                        <a href="#" aria-label="Twitter">✕</a><a href="#" aria-label="YouTube">▶</a>
                    </div>
                </div>
                <div>
                    <h4>Shop</h4>
                    <ul>
                        @foreach($navCategories->take(5) as $c)
                            <li><a href="{{ route('shop.index', ['category' => $c->slug]) }}">{{ $c->name }}</a></li>
                        @endforeach
                        <li><a href="{{ route('shop.index') }}">All Products</a></li>
                    </ul>
                </div>
                <div>
                    <h4>Company</h4>
                    <ul>
                        <li><a href="{{ route('about') }}">About Us</a></li>
                        <li><a href="{{ route('contact') }}">Contact</a></li>
                        <li><a href="{{ route('shop.index') }}">Best Sellers</a></li>
                        <li><a href="#">Blog</a></li>
                    </ul>
                </div>
                <div>
                    <h4>Get in Touch</h4>
                    <ul>
                        <li>📍 Lahore, Pakistan</li>
                        <li>📞 +92 300 1234567</li>
                        <li>✉️ care@herbalroots.pk</li>
                        <li>🕑 Mon–Sat, 9am–7pm</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="foot-bottom container">© {{ date('Y') }} Herbal Roots. All rights reserved. &nbsp;•&nbsp; Crafted with 🌿 for natural living.</div>
    </footer>

    <!-- Cart drawer -->
    <div class="drawer-overlay" id="drawerOverlay"></div>
    <aside class="drawer" id="cartDrawer">
        <div class="drawer-head">
            <h3>Your Cart</h3>
            <button class="icon-btn" data-close-cart>✕</button>
        </div>
        <div class="drawer-body" id="drawerBody"></div>
        <div class="drawer-foot" id="drawerFoot"></div>
    </aside>

    <div id="toast"></div>

    @include('partials.ai-chat')

    <script src="{{ asset('js/site.js') }}"></script>
    @stack('scripts')
</body>
</html>
