@extends('admin.layouts.master')

@section('page_title')
    {{ __('user.profile.title') }} - {{ Auth::user()->name }}
@endsection

@push('css')
<style>
    .admin-profile-page {
        max-width: 1024px;
        margin: 0 auto;
        padding-bottom: 3rem;
    }
    .admin-profile-breadcrumb {
        display: flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.12em;
        color: #94a3b8;
        margin-bottom: 0.35rem;
    }
    .admin-profile-breadcrumb .sep { opacity: 0.6; }
    .admin-profile-breadcrumb .current {
        color: #ca8a04;
        font-weight: 900;
    }
    .admin-profile-page-title {
        font-size: 1.875rem;
        font-weight: 900;
        color: #1e293b;
        letter-spacing: -0.02em;
        margin: 0 0 1.75rem 0;
        padding-left: 2px;
    }
    .admin-profile-card {
        background: #fff;
        border-radius: 32px;
        border: 1px solid #f1f5f9;
        box-shadow: 0 20px 50px -12px rgba(15, 23, 42, 0.12);
        overflow: hidden;
        position: relative;
    }
    .admin-profile-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 128px;
        background: rgba(250, 204, 21, 0.1);
        pointer-events: none;
        z-index: 0;
    }
    .admin-profile-inner {
        position: relative;
        z-index: 1;
        display: flex;
        flex-direction: column;
        gap: 2.5rem;
        padding: 2.5rem 2rem 2.5rem;
    }
    @media (min-width: 992px) {
        .admin-profile-inner {
            flex-direction: row;
            align-items: flex-start;
            gap: 3rem;
            padding: 3rem 2.5rem 2.5rem;
        }
    }
    .admin-profile-aside {
        width: 100%;
        max-width: 280px;
        margin: 0 auto;
        flex-shrink: 0;
    }
    @media (min-width: 992px) {
        .admin-profile-aside {
            margin: 0;
        }
    }
    .admin-profile-photo-wrap {
        position: relative;
        width: 192px;
        height: 192px;
        margin: 0 auto;
    }
    @media (min-width: 992px) {
        .admin-profile-photo-wrap {
            margin: 0;
        }
    }
    .admin-profile-photo {
        width: 100%;
        height: 100%;
        border-radius: 16px;
        overflow: hidden;
        border: 4px solid #fff;
        box-shadow: 0 20px 40px rgba(15, 23, 42, 0.12);
    }
    .admin-profile-photo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }
    .admin-profile-badge {
        position: absolute;
        bottom: -10px;
        right: -10px;
        width: 40px;
        height: 40px;
        background: #facc15;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid #fff;
        box-shadow: 0 6px 16px rgba(15, 23, 42, 0.12);
        color: #0f172a;
        font-size: 18px;
    }
    .admin-profile-joined {
        margin-top: 1.75rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 1rem 1.1rem;
        background: #f8fafc;
        border: 1px solid #f1f5f9;
        border-radius: 16px;
        color: #64748b;
    }
    .admin-profile-joined i {
        color: #ca8a04;
        font-size: 18px;
    }
    .admin-profile-joined .label {
        font-size: 10px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: #94a3b8;
        display: block;
    }
    .admin-profile-joined .value {
        font-size: 14px;
        font-weight: 700;
        color: #334155;
    }
    .admin-profile-main {
        flex: 1;
        min-width: 0;
    }
    .admin-profile-main-head {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #f1f5f9;
        margin-bottom: 1.75rem;
    }
    @media (min-width: 576px) {
        .admin-profile-main-head {
            flex-direction: row;
            align-items: flex-start;
            justify-content: space-between;
        }
    }
    .admin-profile-main-head h3 {
        margin: 0;
        font-size: 1.25rem;
        font-weight: 900;
        color: #1e293b;
        letter-spacing: -0.02em;
    }
    .admin-profile-main-head p {
        margin: 0.35rem 0 0;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.12em;
        color: #94a3b8;
    }
    .btn-admin-save {
        background: #facc15 !important;
        border: none !important;
        color: #0f172a !important;
        font-weight: 900 !important;
        font-size: 11px !important;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        padding: 0.65rem 1.35rem !important;
        border-radius: 12px !important;
        box-shadow: 0 10px 24px rgba(250, 204, 21, 0.35);
        white-space: nowrap;
        align-self: flex-start;
    }
    @media (min-width: 576px) {
        .btn-admin-save { align-self: center; }
    }
    .btn-admin-save:hover {
        background: #eab308 !important;
        color: #0f172a !important;
    }
    .admin-profile-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.75rem 2rem;
    }
    @media (min-width: 768px) {
        .admin-profile-grid {
            grid-template-columns: 1fr 1fr;
        }
    }
    .admin-profile-field label {
        display: block;
        font-size: 10px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.14em;
        color: #94a3b8;
        margin: 0 0 0.45rem 0.25rem;
    }
    .admin-profile-input-wrap {
        position: relative;
    }
    .admin-profile-input-wrap > i.field-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 16px;
        z-index: 2;
        pointer-events: none;
    }
    .admin-profile-input-wrap .form-control {
        padding-left: 2.65rem !important;
        padding-right: 2.75rem !important;
        min-height: 48px !important;
        border-radius: 16px !important;
        background: #f8fafc !important;
        border: 1px solid #e2e8f0 !important;
        font-size: 14px !important;
        font-weight: 700 !important;
        color: #334155 !important;
    }
    .admin-profile-input-wrap .form-control:focus {
        background: #fff !important;
        border-color: #facc15 !important;
        box-shadow: 0 0 0 3px rgba(250, 204, 21, 0.25) !important;
    }
    .admin-profile-input-wrap .input-group-text {
        position: absolute;
        right: 6px;
        top: 50%;
        transform: translateY(-50%);
        border: 0 !important;
        background: transparent !important;
        z-index: 3;
        cursor: pointer;
        color: #94a3b8;
        padding: 0.35rem 0.5rem;
    }
    .admin-profile-input-wrap .input-group-text:hover {
        color: #475569;
    }
    .admin-profile-input-wrap .d-flex {
        position: relative;
        display: block !important;
    }
    .admin-profile-input-wrap .d-flex .form-control {
        width: 100%;
    }
    .admin-profile-security {
        margin-top: 2rem;
        display: flex;
        gap: 1rem;
        padding: 1.35rem 1.5rem;
        background: #fffbeb;
        border: 1px solid #fef3c7;
        border-radius: 24px;
        align-items: flex-start;
    }
    .admin-profile-security-icon {
        flex-shrink: 0;
        width: 40px;
        height: 40px;
        background: #fff;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ca8a04;
        font-size: 20px;
        border: 1px solid #fef3c7;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.06);
    }
    .admin-profile-security h4 {
        margin: 0;
        font-size: 14px;
        font-weight: 900;
        color: #1e293b;
    }
    .admin-profile-security p {
        margin: 0.35rem 0 0;
        font-size: 12px;
        font-weight: 500;
        color: #475569;
        line-height: 1.55;
    }
</style>
@endpush

@section('content')
@php
    $adminUser = Auth::user();
    $joined = $adminUser->created_at
        ? $adminUser->created_at->format('F j, Y')
        : '—';
    $avatarSrc = !empty($adminUser->image) ? asset($adminUser->image) : asset('assets/admin/img/default-user.png');
@endphp
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div class="admin-profile-page">
    <div class="admin-profile-breadcrumb">
        <span>Settings</span>
        <span class="sep">›</span>
        <span class="current">Admin profile</span>
    </div>
    <h1 class="admin-profile-page-title">Admin Settings</h1>

    <div class="admin-profile-card">
        <form method="post" action="{{ route('profile.update', $adminUser->id) }}" autocomplete="off">
            @csrf
            <div class="admin-profile-inner">
                <aside class="admin-profile-aside">
                    <div class="admin-profile-photo-wrap">
                        <div class="admin-profile-photo">
                            <img src="{{ $avatarSrc }}" alt=""
                                onerror="this.src='{{ asset('assets/admin/img/default-user.png') }}'">
                        </div>
                        <div class="admin-profile-badge" title="Verified admin" aria-hidden="true">
                            <i class="fa-solid fa-shield-halved"></i>
                        </div>
                    </div>
                    <div class="admin-profile-joined">
                        <i class="fa-regular fa-calendar"></i>
                        <div>
                            <span class="label">Joined date</span>
                            <span class="value">{{ $joined }}</span>
                        </div>
                    </div>
                </aside>

                <div class="admin-profile-main">
                    <div class="admin-profile-main-head">
                        <div>
                            <h3>Personal information</h3>
                            <p>Manage your account credentials</p>
                        </div>
                        <button type="submit" class="btn btn-admin-save">{{ __('default.form.update-button') }}</button>
                    </div>

                    <div class="admin-profile-grid">
                        <div class="admin-profile-field">
                            <label for="profile-name">{{ __('default.form.name') }}</label>
                            <div class="admin-profile-input-wrap">
                                <i class="fa-regular fa-user field-icon"></i>
                                <input id="profile-name" type="text" name="name" class="form-control"
                                    value="{{ old('name', $adminUser->name) }}">
                            </div>
                        </div>
                        <div class="admin-profile-field">
                            <label for="profile-email">{{ __('default.form.email') }}</label>
                            <div class="admin-profile-input-wrap">
                                <i class="fa-regular fa-envelope field-icon"></i>
                                <input id="profile-email" type="email" name="email" class="form-control"
                                    value="{{ old('email', $adminUser->email) }}">
                            </div>
                        </div>
                        <div class="admin-profile-field">
                            <label for="password">{{ __('default.form.password') }}</label>
                            <div class="admin-profile-input-wrap">
                                <i class="fa-solid fa-lock field-icon"></i>
                                <span class="d-flex">
                                    <input type="password" id="password" name="password"
                                        class="form-control @error('password') is-invalid @enderror"
                                        placeholder="••••••••">
                                    <span class="input-group-text" onclick="togglePassword('password', this)" role="button" tabindex="0">
                                        <i class="fa-solid fa-eye-slash"></i>
                                    </span>
                                </span>
                                @error('password')
                                    <span class="text-danger d-block mt-1 small">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="admin-profile-field">
                            <label for="confirm-password">{{ __('default.form.confirm-password') }}</label>
                            <div class="admin-profile-input-wrap">
                                <i class="fa-solid fa-lock field-icon"></i>
                                <span class="d-flex">
                                    <input type="password" id="confirm-password" name="confirm-password"
                                        class="form-control @error('confirm-password') is-invalid @enderror"
                                        placeholder="••••••••">
                                    <span class="input-group-text" onclick="togglePassword('confirm-password', this)" role="button" tabindex="0">
                                        <i class="fa-solid fa-eye-slash"></i>
                                    </span>
                                </span>
                                @error('confirm-password')
                                    <span class="text-danger d-block mt-1 small">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="admin-profile-security">
                        <div class="admin-profile-security-icon" aria-hidden="true">
                            <i class="fa-solid fa-shield-halved"></i>
                        </div>
                        <div>
                            <h4>Security standards</h4>
                            <p>Your password should be at least 12 characters long and include a mix of uppercase letters, numbers, and symbols to ensure maximum security for your administrative account.</p>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function togglePassword(fieldId, iconSpan) {
    const input = document.getElementById(fieldId);
    const icon = iconSpan.querySelector('i');
    if (!input || !icon) return;
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.add('fa-eye');
        icon.classList.remove('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.add('fa-eye-slash');
        icon.classList.remove('fa-eye');
    }
}
</script>
@endsection
