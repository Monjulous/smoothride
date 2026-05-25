
<!-- Sidebar: brand + footer fixed; middle scrolls (matches admin-UI) -->
<div class="sidebar" id="sidebar">
    <button type="button" class="admin-sidebar-fab-toggle d-none d-lg-flex" id="toggle_btn" aria-label="Toggle sidebar menu" title="Toggle sidebar">
        <svg class="admin-sidebar-fab-icon admin-sidebar-fab-x" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
        <svg class="admin-sidebar-fab-icon admin-sidebar-fab-menu" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true"><path d="M4 6h16"/><path d="M4 12h16"/><path d="M4 18h16"/></svg>
    </button>
    <div class="sidebar-inner admin-sidebar-inner">
        <div class="sidebar-brand">
            <a href="{{ route('dashboard') }}" class="brand-logo">
                @if ($setting->website_logo_dark != null || !empty($setting->website_logo_dark))
                    <img src="{{ asset($setting->website_logo_dark) }}" alt="{{ $setting->website_title }}">
                @else
                    <img src="{{ asset('assets/admin/img/logo.png') }}" alt="Smooth Ride">
                @endif
            </a>
            <p class="admin-console-label">Admin Console</p>
        </div>

        <div class="sidebar-scroll-region">
            <div id="sidebar-menu" class="sidebar-menu">
                <ul>
                    <li class="menu-title">
                        <span class="menu-title-full">Navigation</span>
                        <span class="menu-title-mini">Nav</span>
                    </li>
                    <li class="{{ request()->is('admin/dashboard*') ? 'active' : '' }}">
                        <a href="{{ route('dashboard') }}"><i data-feather="grid"></i><span>Dashboard</span></a>
                    </li>
                    <li class="{{ request()->is('admin/users*') ? 'active' : '' }}">
                        <a href="{{ route('users.index') }}"><i data-feather="users"></i><span>Customers</span></a>
                    </li>
                    <li class="submenu {{ request()->is('vehicle') || request()->is('VehicleBrandModeladd') ? 'active' : '' }}">
                        <a href="javascript:void(0)"><i data-feather="package"></i><span>Vehicles</span><span class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="{{ route('Vehicle.index') }}" class="{{ request()->is('vehicle') ? 'active' : '' }}">Vehicle</a></li>
                            <li><a href="{{ route('VehicleBrandModeladd') }}" class="{{ request()->is('VehicleBrandModeladd') ? 'active' : '' }}">Brand & Model</a></li>
                        </ul>
                    </li>
                    <li class="{{ request()->is('Ride') ? 'active' : '' }}">
                        <a href="{{ route('Ride') }}"><i data-feather="map-pin"></i><span>Rides</span></a>
                    </li>
                    <li class="{{ request()->is('ratingReviews') ? 'active' : '' }}">
                        <a href="{{ route('ratingReviews') }}"><i data-feather="star"></i><span>Ratings & Reviews</span></a>
                    </li>
                    <li class="{{ request()->is('admin/notifications*') ? 'active' : '' }}">
                        <a href="{{ route('notifications.index') }}"><i data-feather="bell"></i><span>Notifications</span></a>
                    </li>
                    <li class="{{ request()->is('admin/offer*') ? 'active' : '' }}">
                        <a href="{{ route('offer.index') }}"><i data-feather="tag"></i><span>Offers</span></a>
                    </li>
                    <li class="menu-title mt-3">
                        <span class="menu-title-full">Support & Docs</span>
                        <span class="menu-title-mini">Spt</span>
                    </li>
                    <li class="submenu {{ request()->is('edit_home') || request()->is('service') || request()->is('testimonial') || request()->is('faq') || request()->is('chats') || request()->is('edit_privacy') || request()->is('edit_terms') || request()->is('contact') ? 'active' : '' }}">
                        <a href="javascript:void(0)"><i data-feather="settings"></i><span>CMS</span><span class="menu-arrow"></span></a>
                        <ul>
                            <li><a href="{{ route('edit_home',1) }}" class="{{ request()->is('edit_home') ? 'active' : '' }}">Home</a></li>
                            <li><a href="{{ route('service') }}" class="{{ request()->is('service') ? 'active' : '' }}">Services</a></li>
                            <li><a href="{{ route('testimonial') }}" class="{{ request()->is('testimonial') ? 'active' : '' }}">Testimonials</a></li>
                            <li><a href="{{ route('faq') }}" class="{{ request()->is('faq') ? 'active' : '' }}">FAQ</a></li>
                            <li><a href="{{ route('chats') }}" class="{{ request()->is('chats') ? 'active' : '' }}">Support</a></li>
                            <li><a href="{{ route('edit_privacy',1) }}" class="{{ request()->is('edit_privacy') ? 'active' : '' }}">Privacy Policy</a></li>
                            <li><a href="{{ route('edit_terms',2) }}" class="{{ request()->is('edit_terms') ? 'active' : '' }}">Terms & Conditions</a></li>
                            <li><a href="{{ route('contact') }}" class="{{ request()->is('contact') ? 'active' : '' }}">Contact</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>

        <div class="sidebar-footer">
            <a href="{{ route('logout') }}" class="sidebar-logout-link">
                <i data-feather="log-out"></i><span class="sidebar-logout-text">Logout</span>
            </a>
        </div>
    </div>
</div>
<!-- /Sidebar -->
