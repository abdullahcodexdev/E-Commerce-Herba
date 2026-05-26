<div class="admin-tabs reveal" style="margin-bottom:1.25rem;flex-wrap:wrap">
    <a href="{{ route('admin.orders.index') }}" class="{{ request()->routeIs('admin.orders.*') || request()->routeIs('admin.dashboard') ? 'active' : '' }}">📋 Orders</a>
    <a href="{{ route('admin.products.index') }}" class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}">📦 Products</a>
    <a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">🗂 Categories</a>
    <a href="{{ route('admin.messages.index') }}" class="{{ request()->routeIs('admin.messages.*') ? 'active' : '' }}">✉️ Messages</a>
</div>
