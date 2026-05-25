@php
    $setting = \App\Models\Setting::find(1);
@endphp

<!DOCTYPE html>
<html dir="ltr" lang="{{ Session::get('locale') }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>
        @if ($setting->website_title != null || !empty($setting->website_title))
            {{ $setting->website_title }}
        @endif | @yield('page_title')
    </title>


    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="content-language" content="{{ Session::get('locale') }}">

    <!-- Favicon -->
    @if ($setting->website_favicon != null || !empty($setting->website_favicon))
        <link rel="shortcut icon" type="image/x-icon" href="{{ asset($setting->website_favicon) }}">
    @else
        <link rel="shortcut icon" type="image/x-icon" href="/assets/admin/img/favicon.png">
    @endif


    <!-- jQuery -->
    <script src="{{ asset('assets/admin/js/jquery-3.2.1.min.js') }}"></script>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('assets/admin/css/bootstrap.min.css') }}">
    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="{{ asset('assets/admin/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- toastr CSS -->
    <!-- <link rel="stylesheet" href="{{ asset('assets/admin/css/toastr.min.css') }}"> -->
    <!-- Feathericon CSS -->
    <link rel="stylesheet" href="{{ asset('assets/admin/css/feathericon.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/plugins/morris/morris.css') }}">

    <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
    <link rel="https://cdn.datatables.net/rowgroup/1.1.1/css/rowGroup.bootstrap4.min.css" />


    <link href=" https://cdn.datatables.net/2.3.2/css/dataTables.dataTables.css" rel="stylesheet">
    <link rel="https://cdn.datatables.net/buttons/3.2.3/css/buttons.dataTables.css" />
    

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>

    <!-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> -->

    <link rel="stylesheet" href="{{ asset('vendor/file-manager/css/file-manager.css') }}/">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css">

    <!-- summernote -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
        integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous">
    </script>

    <link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-bs4.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-bs4.min.js"></script>

    <!-- Main CSS -->
    <link rel="stylesheet" href="{{ asset('assets/admin/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/custom.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">


   
    @stack('css')


    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script> -->
	<!-- Toastr -->
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
	<!-- Styles -->
	<style type="text/css">
		#success{background: green;}
		#error{background: red;}
		#warning{background: coral;}
		#info{background: cornflowerblue;}
		#question{background: grey;}

        html, body, body *:not(.fa):not(.fe) {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif !important;
        }
        body {
            font-family: 'Inter', sans-serif !important;
            background: #f8fafc;
        }
        .main-wrapper {
            background: #f8fafc;
        }
        /* Layout shell aligned with admin-UI (256px sidebar, 80px header) */
        .header {
            background: #facc15 !important;
            border-bottom: 0;
            box-shadow: 0 8px 18px rgba(15, 23, 42, 0.08);
            height: 80px !important;
            left: 256px !important;
            width: calc(100% - 256px) !important;
            display: flex !important;
            align-items: center;
            padding: 0 32px !important;
            float: none !important;
            transition: left 0.3s ease-in-out, width 0.3s ease-in-out !important;
        }
        .admin-shell-header .user-menu {
            float: none !important;
            display: flex !important;
            align-items: center;
            margin-left: auto !important;
            height: 80px;
        }
        .admin-shell-header .user-menu.nav > li > a {
            height: auto !important;
            min-height: 48px;
            line-height: 1.2 !important;
            display: flex !important;
            align-items: center;
            padding: 8px 12px !important;
        }
        .admin-shell-header .user-menu.nav > li > a:hover,
        .admin-shell-header .user-menu.nav > li > a:focus {
            background-color: rgba(253, 224, 71, 0.45) !important;
        }
        .sidebar {
            background: #ffffff !important;
            border-right: 1px solid #e2e8f0;
            box-shadow: none !important;
            width: 256px !important;
            display: flex !important;
            flex-direction: column !important;
            top: 0 !important;
            bottom: 0 !important;
            height: 100vh !important;
            margin-top: 0 !important;
            padding: 0 !important;
            overflow: visible !important;
            z-index: 1050 !important;
            transition: width 0.3s ease-in-out !important;
        }
        .admin-sidebar-inner {
            display: flex !important;
            flex-direction: column !important;
            height: 100% !important;
            min-height: 0 !important;
            overflow: hidden !important;
            padding-top: 0 !important;
        }
        .sidebar-brand {
            flex-shrink: 0 !important;
            padding: 12px 14px 14px !important;
            border-bottom: 1px solid #f1f5f9;
            text-align: center;
            min-height: 7rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .sidebar-scroll-region {
            flex: 1 1 auto !important;
            min-height: 0 !important;
            overflow-y: auto !important;
            overflow-x: hidden !important;
            -webkit-overflow-scrolling: touch;
        }
        .sidebar-scroll-region::-webkit-scrollbar {
            width: 6px;
        }
        .sidebar-scroll-region::-webkit-scrollbar-track {
            background: transparent;
        }
        .sidebar-scroll-region::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 6px;
        }
        .sidebar-scroll-region::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        .sidebar-scroll-region .sidebar-menu {
            padding: 8px 12px 12px !important;
        }
        .sidebar-footer {
            flex-shrink: 0 !important;
            border-top: 1px solid #f1f5f9;
            padding: 12px 14px 16px;
            background: #fff;
        }
        .sidebar-logout-link {
            display: flex !important;
            align-items: center;
            gap: 10px;
            margin: 4px 10px;
            padding: 10px 12px !important;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            color: #64748b !important;
            text-decoration: none !important;
            transition: background 0.2s ease, color 0.2s ease;
        }
        .sidebar-logout-link:hover {
            background: #fef2f2 !important;
            color: #dc2626 !important;
        }
        .sidebar-logout-link svg {
            width: 20px;
            height: 20px;
            flex-shrink: 0;
        }
        body.mini-sidebar .sidebar-logout-text {
            display: none !important;
        }
        body.mini-sidebar .sidebar-logout-link {
            justify-content: center;
            margin: 4px 6px;
            padding: 10px 8px !important;
        }
        .sidebar-brand .brand-logo {
            display: block;
            margin: 4px 8px 2px;
            padding: 4px 6px;
            line-height: 0;
        }
        .sidebar-brand .brand-logo img {
            width: 100%;
            max-width: 236px;
            max-height: 80px;
            object-fit: contain;
            display: block;
            margin: 0 auto;
        }
        .sidebar-brand p.admin-console-label {
            margin: 10px 0 0;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.14em;
            color: #94a3b8;
            font-weight: 800;
        }
        body.mini-sidebar:not(.expand-menu) .sidebar-brand .admin-console-label {
            display: none !important;
        }
        .sidebar-menu ul li.menu-title .menu-title-full {
            color: #94a3b8;
            font-size: 11px;
            letter-spacing: .08em;
            text-transform: uppercase;
            font-weight: 700;
        }
        .sidebar-menu ul li.menu-title .menu-title-mini {
            display: none !important;
            color: #94a3b8;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            text-align: center;
            width: 100%;
        }
        body.mini-sidebar:not(.expand-menu) .sidebar-menu ul li.menu-title .menu-title-full {
            display: none !important;
        }
        body.mini-sidebar:not(.expand-menu) .sidebar-menu ul li.menu-title .menu-title-mini {
            display: block !important;
        }
        body.mini-sidebar.expand-menu .sidebar-menu ul li.menu-title .menu-title-mini {
            display: none !important;
        }
        body.mini-sidebar.expand-menu .sidebar-menu ul li.menu-title .menu-title-full {
            display: inline !important;
        }
        /* Theme hides .menu-title in mini-sidebar — show abbreviated Nav / Spt (admin-UI) */
        body.mini-sidebar .sidebar #sidebar-menu ul li.menu-title {
            visibility: visible !important;
            opacity: 1 !important;
            height: auto !important;
            min-height: 0 !important;
        }
        .sidebar-menu ul li a {
            margin: 4px 10px;
            border-radius: 12px;
            color: #475569 !important;
            font-weight: 600;
            transition: all .2s ease;
            font-size: 14px;
            padding: 10px 12px;
        }
        .sidebar-menu ul li a i { width: 18px; height: 18px; }
        
        /* Active top-level item */
        .sidebar-menu ul li.active > a {
            background: #facc15 !important; /* admin-UI yellow-400 */
            color: #0f172a !important;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }
        
        /* Hover top-level item */
        .sidebar-menu ul li a:hover,
        .sidebar#sidebar #sidebar-menu > ul > li > a:hover,
        .sidebar#sidebar #sidebar-menu > ul > li.submenu > a:hover {
            background: #f8fafc !important; /* admin-UI slate-50 */
            color: #0f172a !important;
        }
        
        /* Keep active background when hovered */
        .sidebar-menu ul li.active > a:hover {
            background: #facc15 !important;
        }
        
        .sidebar-menu ul ul {
            background: rgba(248, 250, 252, 0.9);
            border-radius: 12px;
            padding: 4px 0;
            margin: 4px 10px 10px !important;
        }
        .sidebar-menu ul ul a {
            margin-left: 8px !important;
            margin-right: 8px !important;
            background: transparent !important;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            color: #64748b !important;
        }
        
        /* Active sub-item */
        .sidebar-menu ul ul li a.active {
            color: #ca8a04 !important; /* admin-UI yellow-600 */
            background: #ffffff !important;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.06);
        }
        
        /* Hover sub-item */
        .sidebar-menu ul ul li a:hover {
            color: #0f172a !important;
            background: rgba(255, 255, 255, 0.5) !important;
        }
        
        /* Replace FontAwesome arrow with Lucide-style SVG Chevron */
        .sidebar#sidebar #sidebar-menu .menu-arrow {
            display: inline-block;
            width: 14px !important;
            height: 14px !important;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='9 18 15 12 9 6'%3E%3C/polyline%3E%3C/svg%3E") !important;
            background-size: contain !important;
            background-repeat: no-repeat !important;
            background-position: center !important;
            position: absolute !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            right: 14px !important;
            transition: transform 0.2s ease !important;
        }
        .sidebar#sidebar #sidebar-menu .menu-arrow:before {
            content: none !important;
        }
        .sidebar#sidebar #sidebar-menu li a.subdrop .menu-arrow {
            transform: translateY(-50%) rotate(90deg) !important;
        }
        
        .sidebar#sidebar #sidebar-menu > ul > li.submenu > a {
            position: relative;
            padding-right: 40px !important;
        }
        .sidebar #sidebar-menu > ul > li:not(.menu-title) > a {
            display: flex !important;
            align-items: center !important;
            gap: 0.65rem;
        }
        /* Floating sidebar toggle (admin-UI): X when expanded, hamburger when mini) */
        .sidebar .admin-sidebar-fab-toggle {
            position: absolute;
            right: -12px;
            top: 7rem;
            z-index: 1055;
            width: 24px;
            height: 24px;
            border-radius: 999px;
            background: #fff !important;
            border: 1px solid #e2e8f0 !important;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.12);
            align-items: center;
            justify-content: center;
            padding: 0 !important;
            margin: 0 !important;
            cursor: pointer;
            color: #334155 !important;
            line-height: 0 !important;
            float: none !important;
        }
        .sidebar .admin-sidebar-fab-toggle:hover {
            background: #f8fafc !important;
            color: #0f172a !important;
        }
        .sidebar .admin-sidebar-fab-toggle:focus {
            outline: 2px solid rgba(250, 204, 21, 0.7);
            outline-offset: 2px;
        }
        .admin-sidebar-fab-icon {
            display: none;
            flex-shrink: 0;
        }
        body:not(.mini-sidebar) .admin-sidebar-fab-x {
            display: block;
        }
        body.mini-sidebar .admin-sidebar-fab-menu {
            display: block;
        }
        @media (min-width: 992px) {
            #sidebar #toggle_btn.admin-sidebar-fab-toggle {
                display: flex !important;
                float: none !important;
                height: 24px !important;
                width: 24px !important;
                margin-left: 0 !important;
                padding: 0 !important;
                font-size: 0 !important;
            }
        }
        @media (max-width: 991.98px) {
            .sidebar .admin-sidebar-fab-toggle {
                display: none !important;
            }
        }
        /* Feather icons: theme CSS used white strokes on hover/active — invisible on yellow */
        .sidebar #sidebar-menu svg.feather,
        .sidebar .sidebar-logout-link svg.feather {
            display: inline-block !important;
            visibility: visible !important;
            opacity: 1 !important;
        }
        .sidebar #sidebar-menu > ul > li > a svg.feather,
        .sidebar #sidebar-menu > ul > li > a svg {
            width: 20px !important;
            height: 20px !important;
            min-width: 20px;
            min-height: 20px;
            flex-shrink: 0;
            stroke: currentColor !important;
            color: #94a3b8 !important; /* admin-UI slate-400 */
            overflow: visible !important;
            transition: color 0.2s ease;
        }
        .sidebar #sidebar-menu > ul > li > a:hover svg.feather,
        .sidebar #sidebar-menu > ul > li > a:hover svg,
        .sidebar #sidebar-menu > ul > li > a:focus svg.feather,
        .sidebar #sidebar-menu > ul > li > a:focus svg {
            color: #0f172a !important; /* admin-UI slate-900 */
            stroke: #0f172a !important;
        }
        .sidebar #sidebar-menu li.active > a svg.feather,
        .sidebar #sidebar-menu li.active > a svg,
        .sidebar #sidebar-menu li.active a svg.feather,
        .sidebar #sidebar-menu li.active a svg {
            color: #0f172a !important; /* admin-UI slate-900 */
            stroke: #0f172a !important;
        }
        .sidebar .sidebar-logout-link svg.feather,
        .sidebar .sidebar-logout-link svg {
            width: 20px !important;
            height: 20px !important;
            stroke: currentColor !important;
            color: #64748b !important;
        }
        .sidebar .sidebar-logout-link:hover svg.feather,
        .sidebar .sidebar-logout-link:hover svg {
            color: #dc2626 !important;
            stroke: #dc2626 !important;
        }
        .page-wrapper {
            background: #fdfdfd;
            margin-left: 256px !important;
            padding-top: 80px !important;
            transition: margin-left 0.3s ease-in-out !important;
        }
        .page-wrapper > .content {
            margin-top: 0;
            padding: 32px 32px 48px !important;
        }
        .card {
            border: 1px solid #e2e8f0 !important;
            border-radius: 20px !important;
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.05) !important;
        }
        .breadcrumb-card {
            background: #fff !important;
        }
        .top-nav-search {
            flex: 1;
            max-width: 672px;
            margin: 0 16px 0 12px;
        }
        .top-nav-search form {
            margin: 0;
            display: flex;
            align-items: center;
            width: 100%;
            height: 42px;
            border-radius: 999px;
            background: rgba(253, 224, 71, 0.4);
            border: 0;
            padding: 0 16px;
        }
        .top-nav-search .search-icon {
            color: #475569;
            font-size: 14px;
            margin-right: 10px;
            line-height: 1;
        }
        .top-nav-search input {
            border-radius: 999px !important;
            border: 0 !important;
            background: transparent !important;
            min-width: 0;
            width: 100%;
            height: 42px;
            font-size: 14px;
            font-weight: 500;
            color: #0f172a;
        }
        .top-nav-search input:focus {
            outline: none !important;
            box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.45) !important;
        }
        .top-nav-search input::placeholder { color: #64748b; }
        .user-menu .nav-link {
            color: #0f172a !important;
        }
        .welcome-user {
            flex-direction: column;
            align-items: flex-end;
            justify-content: center;
            line-height: 1.1;
            color: #0f172a;
            margin-top: 1px;
        }
        .welcome-user .welcome-label {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .08em;
            opacity: .7;
        }
        .welcome-user .welcome-name {
            font-size: 14px;
            font-weight: 900;
            letter-spacing: .02em;
            margin-top: 2px;
        }
        .user-menu .count {
            background: #ef4444 !important;
        }
        .admin-noti-bell {
            padding: 0 6px !important;
        }
        .admin-noti-bell-inner {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 44px;
            height: 44px;
            border-radius: 999px;
            background: rgba(253, 224, 71, 0.55);
        }
        .admin-noti-bell:hover .admin-noti-bell-inner {
            background: rgba(253, 224, 71, 0.9);
        }
        .admin-noti-bell .fe {
            font-size: 20px;
            line-height: 1 !important;
        }
        .admin-noti-bell .count {
            position: absolute;
            top: 2px;
            right: 0;
            min-width: 18px;
            height: 18px;
            line-height: 16px;
            padding: 0 4px;
            font-size: 10px !important;
            font-weight: 800;
            border-radius: 999px;
            background: #ef4444 !important;
            color: #fff !important;
            border: 2px solid #facc15;
        }
        .user-menu .has-arrow .dropdown-toggle {
            display: flex;
            align-items: center;
            gap: 10px;
            padding-right: 4px !important;
        }
        .admin-user-toggle {
            border-left: 1px solid rgba(15, 23, 42, 0.12) !important;
            margin-left: 12px !important;
            padding-left: 22px !important;
        }
        .user-menu .user-img img,
        .admin-header-avatar {
            width: 40px !important;
            height: 40px !important;
            object-fit: cover;
            border: 2px solid #fff !important;
            box-shadow: 0 1px 3px rgba(15, 23, 42, 0.12);
        }
        .dropdown-menu.notifications {
            width: 380px !important;
            max-width: calc(100vw - 24px);
            border-radius: 16px !important;
            border: 1px solid #f1f5f9 !important;
            padding: 0 !important;
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.18) !important;
        }
        .dropdown-menu.notifications .topnav-dropdown-header {
            display: flex !important;
            align-items: center;
            justify-content: space-between !important;
            float: none !important;
            height: auto !important;
            line-height: 1.3 !important;
            padding: 18px 20px !important;
            text-align: left !important;
            border-bottom: 1px solid #f1f5f9 !important;
            background: #fff;
        }
        .dropdown-menu.notifications .notification-title {
            float: none !important;
            font-size: 13px !important;
            font-weight: 900 !important;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: #1e293b !important;
        }
        .dropdown-menu.notifications .clear-noti {
            float: none !important;
            font-size: 10px !important;
            font-weight: 900 !important;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: #ef4444 !important;
        }
        .dropdown-menu.notifications .noti-content {
            width: 100% !important;
            height: auto !important;
            max-height: 400px !important;
        }
        .dropdown-menu.notifications .noti-item-link {
            padding: 18px 20px !important;
        }
        .dropdown-menu.notifications .noti-item-msg {
            font-size: 13px !important;
            font-weight: 800 !important;
            color: #334155 !important;
            line-height: 1.35 !important;
            margin-bottom: 0 !important;
        }
        .dropdown-menu.notifications .noti-name {
            color: #0f172a !important;
        }
        .dropdown-menu.notifications .notification-time {
            font-size: 10px !important;
            font-weight: 800 !important;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #94a3b8 !important;
        }
        .dropdown-menu.notifications .noti-avatar-wrap {
            width: 40px !important;
            height: 40px !important;
            flex-shrink: 0;
        }
        .dropdown-menu.notifications .noti-avatar-img {
            width: 40px !important;
            height: 40px !important;
            object-fit: cover;
            border: 1px solid #e2e8f0;
        }
        .dropdown-menu.notifications .noti-item-media {
            align-items: flex-start !important;
            border-bottom: 1px solid #f8fafc !important;
        }
        .dropdown-menu.notifications ul.notification-list > li:last-child .noti-item-media {
            border-bottom: 0 !important;
        }
        .dropdown-menu.notifications .topnav-dropdown-footer {
            border-top: 1px solid #f1f5f9 !important;
            background: #f8fafc;
            padding: 14px 16px !important;
            height: auto !important;
            line-height: 1.3 !important;
        }
        .dropdown-menu.notifications .noti-view-all {
            display: block;
            text-align: center;
            font-size: 10px !important;
            font-weight: 900 !important;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: #64748b !important;
            text-decoration: none !important;
        }
        .dropdown-menu.notifications .noti-view-all:hover {
            color: #0f172a !important;
        }
        .dropdown-menu.notifications .noti-empty-state {
            padding: 2.5rem 1.5rem 2.75rem;
            text-align: center;
        }
        .dropdown-menu.notifications .noti-empty-icon {
            display: inline-flex;
            width: 48px;
            height: 48px;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            background: #f8fafc;
            color: #cbd5e1;
            font-size: 22px;
            margin-bottom: 0.35rem;
        }
        .dropdown-menu.notifications .noti-empty-text {
            font-size: 11px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            color: #94a3b8;
            margin: 0;
        }
        .table thead th {
            background: #f8fafc !important;
            color: #64748b !important;
            font-size: 10px !important;
            font-weight: 800 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.08em !important;
            border-bottom: 1px solid #f1f5f9 !important;
            border-top: 0 !important;
        }
        .table tbody td {
            border-color: #f1f5f9 !important;
            color: #334155;
            font-weight: 500;
        }
        .btn-dark,
        .btn-primary {
            background: #facc15 !important;
            border-color: #facc15 !important;
            color: #0f172a !important;
            font-weight: 700;
            border-radius: 12px;
        }
        .btn-outline-primary,
        .btn-outline-warning,
        .btn-outline-success,
        .btn-outline-danger {
            border-radius: 10px;
            font-weight: 600;
        }
        .form-control,
        .custom-select,
        select.form-control {
            border-radius: 12px !important;
            border: 1px solid #cbd5e1 !important;
            min-height: 40px;
            box-shadow: none !important;
        }
        .form-control:focus,
        .custom-select:focus {
            border-color: #facc15 !important;
            box-shadow: 0 0 0 0.15rem rgba(250, 204, 21, 0.25) !important;
        }
        .modal-content {
            border-radius: 18px;
            border: 1px solid #e2e8f0;
        }
        .page-title {
            font-weight: 800;
            color: #0f172a;
        }
        .breadcrumb-item a {
            color: #64748b !important;
            font-weight: 600;
        }
        .active-breadcrumb a {
            color: #0f172a !important;
        }
        .dataTables_wrapper .dataTables_filter input,
        .dataTables_wrapper .dataTables_length select {
            border-radius: 10px !important;
            border: 1px solid #cbd5e1 !important;
            padding: 6px 10px !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: #facc15 !important;
            border-color: #facc15 !important;
            color: #0f172a !important;
            border-radius: 8px;
        }
        .alert {
            border-radius: 12px;
            border: 0;
        }
        .dropdown-menu {
            border-radius: 14px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 18px 38px rgba(15,23,42,.08);
        }
        .badge, .status-badge {
            border-radius: 999px;
            padding: .35rem .6rem;
        }
        @media (min-width: 992px) {
            body.mini-sidebar #sidebar.sidebar {
                width: 80px !important;
            }
            body.mini-sidebar:not(.expand-menu) .sidebar#sidebar #sidebar-menu > ul > li:not(.menu-title) > a,
            body.mini-sidebar:not(.expand-menu) .sidebar#sidebar #sidebar-menu > ul > li.submenu > a {
                display: flex !important;
                justify-content: center !important;
                align-items: center !important;
                padding: 10px 6px !important;
            }
            body.mini-sidebar:not(.expand-menu) .sidebar#sidebar #sidebar-menu > ul > li > a > i,
            body.mini-sidebar:not(.expand-menu) .sidebar#sidebar #sidebar-menu > ul > li > a > svg {
                margin-right: 0 !important;
            }
            body.mini-sidebar:not(.expand-menu) .sidebar#sidebar #sidebar-menu .menu-arrow {
                display: none !important;
            }
            body.mini-sidebar:not(.expand-menu) .sidebar-menu ul li.menu-title {
                padding-left: 4px !important;
                padding-right: 4px !important;
                justify-content: center;
            }
            body.mini-sidebar.expand-menu .sidebar-brand .admin-console-label {
                display: block !important;
            }
            body.mini-sidebar .sidebar-brand {
                padding: 10px 6px 12px !important;
            }
            body.mini-sidebar .sidebar-brand .brand-logo {
                margin: 2px 4px;
                padding: 2px;
            }
            body.mini-sidebar .sidebar-brand .brand-logo img {
                max-width: 56px;
                max-height: 40px;
            }
            body.mini-sidebar .page-wrapper {
                margin-left: 80px !important;
            }
            body.mini-sidebar .header.admin-shell-header {
                left: 80px !important;
                width: calc(100% - 80px) !important;
            }
            body.mini-sidebar.expand-menu .sidebar {
                width: 256px !important;
            }
            body.mini-sidebar.expand-menu .header.admin-shell-header {
                left: 256px !important;
                width: calc(100% - 256px) !important;
            }
        }
        @media (max-width: 991.98px) {
            .sidebar {
                margin-left: -256px !important;
                width: 256px !important;
            }
            .slide-nav .sidebar {
                margin-left: 0 !important;
                z-index: 1055 !important;
            }
            .header {
                left: 0;
                width: 100%;
            }
            .page-wrapper {
                margin-left: 0;
            }
            .page-wrapper > .content {
                padding: 16px !important;
            }
            .top-nav-search {
                margin: 0 8px;
            }
            .top-nav-search input {
                min-width: 180px;
            }
        }
	</style>
    <link rel="stylesheet" href="{{ asset('assets/admin/css/admin-ui-parity.css') }}">

<!-- <script src="https://js.pusher.com/7.2/pusher.min.js"></script> -->


</head>

<body>
    <!-- Sidebar State Initialization -->
    <script>
        if (localStorage.getItem('sidebarState') === 'collapsed') {
            document.body.classList.add('mini-sidebar');
        }
    </script>

    <!-- Main Wrapper -->
    <div class="main-wrapper">

        @include('admin.layouts.header')
        @include('admin.layouts.sidebar')

        <!-- Page Wrapper -->
        <div class="page-wrapper">
            <div class="content container-fluid">
                @yield('content')
            </div>
        </div>

    </div>
    <!-- /Main Wrapper -->


    <!-- Bootstrap Core JS -->
    <script src="{{ asset('assets/admin/js/popper.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/bootstrap.min.js') }}"></script>

    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>
    <!-- <script src="https://cdn.tiny.cloud/1/hacu5s8ld7b5xx9hdo1laufa5yvhr6s48c38wigwc3gfarik/tinymce/5/tinymce.min.js" -->
        <!-- referrerpolicy="origin"></script> -->

    <!-- Slimscroll JS -->
    <script src="{{ asset('assets/admin/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>
    <script src="{{ asset('assets/admin/plugins/raphael/raphael.min.js') }}"></script>
    <script src="{{ asset('assets/admin/plugins/morris/morris.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/chart.morris.js') }}"></script>

    <!-- toastr JS -->
    <!-- <script src="{{ asset('assets/admin/js/toastr.min.js') }}"></script>
    {!! Toastr::message() !!} -->


    <!-- Custom JS -->
    <script src="{{ asset('assets/admin/js/script.js') }}"></script>
    <script src="https://unpkg.com/feather-icons@4.29.0/dist/feather.min.js"></script>
    <script src="{{ asset('vendor/file-manager/js/file-manager.js') }}"></script>

    <script>
        window.setTimeout(function() {
            $(".alert").fadeTo(500, 0).slideUp(500, function() {
                $(this).remove();
            });
        }, 5000);
    </script>

    <script type="text/javascript">
        function adminRenderFeatherIcons() {
            if (typeof feather === 'undefined') return;
            feather.replace({
                width: 20,
                height: 20,
                'stroke-width': 2
            });
        }

        $(function () {
            // Apply responsive table behavior globally for all admin pages.
            $('table').each(function () {
                if (!$(this).parent().hasClass('table-responsive')) {
                    $(this).wrap('<div class="table-responsive"></div>');
                }
            });
            adminRenderFeatherIcons();
        });

        window.addEventListener('load', function () {
            adminRenderFeatherIcons();
        });

        // $(document).ready(function() {
        //     $('select').select2();
        // });

        $(document).ready(function() {
			toastr.options = {
				'closeButton': true,
				'debug': false,
				'newestOnTop': false,
				'progressBar': false,
				'positionClass': 'toast-top-center',
				'preventDuplicates': false,
				'showDuration': '1000',
				'hideDuration': '1000',
				'timeOut': '5000',
				'extendedTimeOut': '1000',
				'showEasing': 'swing',
				'hideEasing': 'linear',
				'showMethod': 'fadeIn',
				'hideMethod': 'fadeOut',
			}
		});

        // toastr.success('You clicked Success toast');


            $(document).ready(function() {


             toastr.options.timeOut = 100000000000000000;
            // toastr.success('testinggggggggggggggggggggggggggggggg');

            var serr = '<?php echo Session::get('error') ?>';
            var sucess = '<?php echo Session::get('success') ?>';

            @if(Session::has('error'))

            toastr.error(serr);

            @elseif(Session::has('success'))

            toastr.success(sucess)

            @endif
            });
    </script>

<!-- <script src="https://js.pusher.com/8.4.0/pusher.min.js"></script> -->


<script scr="https://code.jquery.com/jquery-3.7.1.js"></script>
<script scr="https://cdn.datatables.net/2.3.2/js/dataTables.js"></script>
<script scr="https://cdn.datatables.net/buttons/3.2.3/js/dataTables.buttons.js"></script>
<script scr="https://cdn.datatables.net/buttons/3.2.3/js/buttons.dataTables.js"></script>
<script scr="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script scr="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script scr="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script scr="https://cdn.datatables.net/buttons/3.2.3/js/buttons.html5.min.js"></script>
<script scr="https://cdn.datatables.net/buttons/3.2.3/js/buttons.print.min.js"></script>



<!-- <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
<!-- <script src="https://www.gstatic.com/firebasejs/8.6.8/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.6.8/firebase-auth.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.6.8/firebase-database.js"></script> -->

<script src="https://www.gstatic.com/firebasejs/6.6.1/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/6.6.1/firebase-database.js"></script>

<script src="{{ asset('firebaseConfig.js')}}"></script>


<script type="text/javascript">
    $(document).ready(function () {
        let my_id = '{{ Auth::user()->id }}';
        if (my_id) {
            show_notifications(my_id);
        }
    });
    
    function show_notifications(userId) {
        const receiverProfileImg = $('#receiverProfileImage').val(); // Make sure this input exists
        const notificationsRef = firebase.database().ref('Notification/' + userId);
    
        notificationsRef.on("value", function (snapshot) {
            snapshot.forEach(function (childSnapshot) {
                const notificationData = childSnapshot.val();
    
                // Show only unseen notifications
                if (notificationData && notificationData.seen !== true) {
                    const title = notificationData.message || "New Notification";
                    const body = notificationData.body || "You have a new message";
                    const icon = notificationData.senderImage || receiverProfileImg || '{{ asset('assets/admin/img/default-user.png') }}'; // fallback icon
                    const clickUrl = notificationData.url || '/'; // fallback URL
    
                    // Create browser notification
                    if (Notification.permission === "granted") {
                        showBrowserNotification(title, body, icon, clickUrl);
                    } else if (Notification.permission !== "denied") {
                        Notification.requestPermission().then(permission => {
                            if (permission === "granted") {
                                showBrowserNotification(title, body, icon, clickUrl);
                            }
                        });
                    }
    
                  
                }
            });
        });
    }
    
    function showBrowserNotification(title, body, icon, url) {
        const notification = new Notification(title, {
            body: body,
            icon: icon
        });
    
        // Make it clickable
        notification.onclick = function (event) {
            event.preventDefault(); // prevent default action
            window.open(url);
        };
    }
    </script>
    

    @stack('scripts')

</body>

</html>
