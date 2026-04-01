@php
    $firmName = \App\Models\Setting::get('salon_name', 'AutoDetail Pro');
    $logoPath = \App\Models\Setting::get('logo', '');
@endphp
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Admin Panel' }} - {{ $firmName }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .sidebar-link { display:flex; align-items:center; gap:12px; padding:10px 16px; border-radius:12px; font-size:14px; font-weight:500; transition:all .2s; }
        .sidebar-link.active { background:rgba(6,182,212,0.15); color:#06b6d4; }
        .sidebar-link:not(.active) { color:#94a3b8; }
        .sidebar-link:not(.active):hover { background:rgba(255,255,255,0.05); color:#e2e8f0; }
        .section-title { color:#475569; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.08em; padding:0 16px; margin-bottom:6px; margin-top:24px; }
    </style>
    @livewireStyles
</head>
<body class="min-h-screen" style="background:#f1f5f9" x-data="{ mobileSidebar: false }">
<div class="flex min-h-screen">

    {{-- ── SIDEBAR ────────────────────────────────────────── --}}
    <aside class="hidden lg:flex flex-col w-64 fixed h-full z-30 shadow-xl" style="background:linear-gradient(160deg, #0d2137 0%, #0f2d3f 100%)">

        {{-- Logo --}}
        <div class="p-5 border-b" style="border-color:rgba(255,255,255,0.07)">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 overflow-hidden" style="background:rgba(6,182,212,0.15); border:1px solid rgba(6,182,212,0.3)">
                    @if($logoPath)
                        <img src="{{ Storage::url($logoPath) }}" alt="{{ $firmName }}" class="w-full h-full object-contain p-1">
                    @else
                        <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10l2 1h6l2-1z"/>
                        </svg>
                    @endif
                </div>
                <div class="min-w-0">
                    <h1 class="font-bold text-white text-base leading-tight truncate">{{ $firmName }}</h1>
                    <p class="text-xs" style="color:#64748b">Yönetim Paneli</p>
                </div>
            </div>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 p-4 overflow-y-auto space-y-0.5">
            <p class="section-title">Ana Menü</p>
            <a href="/admin" class="sidebar-link {{ request()->is('admin') && !request()->is('admin/*') ? 'active' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                Özet
            </a>
            <a href="/admin/is-emirleri" class="sidebar-link {{ request()->is('admin/is-emirleri*') ? 'active' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                İş Emirleri
                @php $bekleyen = \App\Models\WorkOrder::where('status','bekleyen')->count(); @endphp
                @if($bekleyen > 0)
                    <span class="ml-auto bg-cyan-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $bekleyen }}</span>
                @endif
            </a>
            <a href="/admin/takvim" class="sidebar-link {{ request()->is('admin/takvim*') ? 'active' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Takvim
            </a>

            <p class="section-title">Finans</p>
            <a href="/admin/odemeler" class="sidebar-link {{ request()->is('admin/odemeler*') ? 'active' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                Ödemeler & Cari
            </a>
            <a href="/admin/raporlar" class="sidebar-link {{ request()->is('admin/raporlar*') ? 'active' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                Raporlar
            </a>
            <a href="/admin/gelir-gider" class="sidebar-link {{ request()->is('admin/gelir-gider*') ? 'active' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Gelir / Gider
            </a>

            <p class="section-title">Yönetim</p>
            <a href="/admin/uyeler" class="sidebar-link {{ request()->is('admin/uyeler*') ? 'active' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Müşteriler
            </a>
            <a href="/admin/urunler" class="sidebar-link {{ request()->is('admin/urunler*') ? 'active' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                Ürünler & Stok
            </a>
            <a href="/admin/hizmetler" class="sidebar-link {{ request()->is('admin/hizmetler*') ? 'active' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                Hizmetler
            </a>
            <a href="/admin/personel" class="sidebar-link {{ request()->is('admin/personel*') ? 'active' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                Personel
            </a>

            <p class="section-title">Sistem</p>
            <a href="/admin/ayarlar" class="sidebar-link {{ request()->is('admin/ayarlar*') ? 'active' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Ayarlar
            </a>
            <a href="/admin/yonetim" class="sidebar-link {{ request()->is('admin/yonetim*') ? 'active' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                Kullanıcı Yönetimi
            </a>
        </nav>

        {{-- User --}}
        <div class="p-4" style="border-top:1px solid rgba(255,255,255,0.07)">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center text-sm font-bold text-white flex-shrink-0" style="background:rgba(6,182,212,0.2); border:1px solid rgba(6,182,212,0.3)">
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-white truncate">{{ auth()->user()->name ?? 'Admin' }}</p>
                    <p class="text-xs" style="color:#64748b">{{ auth()->user()->role === 'super' ? 'Super Admin' : 'Personel' }}</p>
                </div>
                <form method="POST" action="/logout">
                    @csrf
                    <button type="submit" class="p-1.5 rounded-lg transition hover:bg-white/10" title="Çıkış" style="color:#64748b">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- ── MOBİL HEADER ────────────────────────────────────── --}}
    <div class="lg:hidden fixed top-0 left-0 right-0 z-20 flex items-center justify-between px-4 py-3 shadow-lg" style="background:linear-gradient(90deg,#0d2137,#0f2d3f)">
        <button @click="mobileSidebar = true" class="p-2 rounded-lg text-slate-400 hover:text-white hover:bg-white/10 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
        <span class="font-bold text-white text-sm">{{ $firmName }}</span>
        <form method="POST" action="/logout">
            @csrf
            <button type="submit" class="text-xs text-slate-400 hover:text-white transition p-2">Çıkış</button>
        </form>
    </div>

    {{-- ── MOBİL SIDEBAR ───────────────────────────────────── --}}
    <div x-show="mobileSidebar" x-transition.opacity class="lg:hidden fixed inset-0 bg-black/60 z-30" @click="mobileSidebar = false"></div>
    <aside x-show="mobileSidebar" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full"
        class="lg:hidden fixed inset-y-0 left-0 w-72 z-40 flex flex-col shadow-2xl" style="background:linear-gradient(160deg, #0d2137 0%, #0f2d3f 100%)">
        <div class="p-4 flex items-center justify-between" style="border-bottom:1px solid rgba(255,255,255,0.07)">
            <span class="font-bold text-white">{{ $firmName }}</span>
            <button @click="mobileSidebar = false" class="text-slate-400 hover:text-white p-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <nav class="flex-1 p-4 overflow-y-auto space-y-0.5">
            <p class="section-title">Ana Menü</p>
            <a href="/admin" class="sidebar-link {{ request()->is('admin') && !request()->is('admin/*') ? 'active' : '' }}" @click="mobileSidebar=false">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                Özet
            </a>
            <a href="/admin/is-emirleri" class="sidebar-link {{ request()->is('admin/is-emirleri*') ? 'active' : '' }}" @click="mobileSidebar=false">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                İş Emirleri
            </a>
            <p class="section-title">Finans</p>
            <a href="/admin/odemeler" class="sidebar-link {{ request()->is('admin/odemeler*') ? 'active' : '' }}" @click="mobileSidebar=false">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                Ödemeler & Cari
            </a>
            <a href="/admin/raporlar" class="sidebar-link {{ request()->is('admin/raporlar*') ? 'active' : '' }}" @click="mobileSidebar=false">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10"/></svg>
                Raporlar
            </a>
            <a href="/admin/gelir-gider" class="sidebar-link {{ request()->is('admin/gelir-gider*') ? 'active' : '' }}" @click="mobileSidebar=false">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Gelir / Gider
            </a>
            <p class="section-title">Yönetim</p>
            <a href="/admin/uyeler" class="sidebar-link {{ request()->is('admin/uyeler*') ? 'active' : '' }}" @click="mobileSidebar=false">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Müşteriler
            </a>
            <a href="/admin/urunler" class="sidebar-link {{ request()->is('admin/urunler*') ? 'active' : '' }}" @click="mobileSidebar=false">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                Ürünler & Stok
            </a>
            <a href="/admin/hizmetler" class="sidebar-link {{ request()->is('admin/hizmetler*') ? 'active' : '' }}" @click="mobileSidebar=false">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                Hizmetler
            </a>
            <a href="/admin/personel" class="sidebar-link {{ request()->is('admin/personel*') ? 'active' : '' }}" @click="mobileSidebar=false">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                Personel
            </a>
            <p class="section-title">Sistem</p>
            <a href="/admin/ayarlar" class="sidebar-link {{ request()->is('admin/ayarlar*') ? 'active' : '' }}" @click="mobileSidebar=false">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Ayarlar
            </a>
        </nav>
    </aside>

    {{-- ── ANA İÇERİK ──────────────────────────────────────── --}}
    <main class="flex-1 lg:ml-64 mt-14 lg:mt-0">
        <div class="p-4 sm:p-6 lg:p-8 max-w-7xl mx-auto">
            {{ $slot }}
        </div>
    </main>
</div>
@livewireScripts
</body>
</html>
