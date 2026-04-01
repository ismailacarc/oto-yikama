@php
    $firmName   = \App\Models\Setting::get('salon_name', 'AutoDetail Pro');
    $themeColor = \App\Models\Setting::get('theme_color', '#111827');
    $accentColor = \App\Models\Setting::get('accent_color', '#06b6d4');
    $logoPath   = \App\Models\Setting::get('logo', '');
    $phone      = \App\Models\Setting::get('phone', '');
@endphp
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? $firmName }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');
        * { font-family: 'Inter', sans-serif; }
        .accent { color: {{ $accentColor }}; }
        .accent-bg { background-color: {{ $accentColor }}; }
        .accent-border { border-color: {{ $accentColor }}; }
        .hero-gradient {
            background: linear-gradient(135deg, #0f0f0f 0%, #1a1a2e 50%, #16213e 100%);
        }
        .glass-card {
            background: rgba(255,255,255,0.04);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255,255,255,0.08);
        }
        .fade-up { animation: fadeUp 0.6s ease-out both; }
        .fade-up-delay { animation: fadeUp 0.6s ease-out 0.2s both; }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .glow {
            box-shadow: 0 0 30px rgba(245,158,11,0.15);
        }
        .btn-accent {
            background: {{ $accentColor }};
            transition: all 0.2s;
        }
        .btn-accent:hover {
            filter: brightness(1.1);
            transform: translateY(-1px);
            box-shadow: 0 8px 25px rgba(245,158,11,0.35);
        }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #111; }
        ::-webkit-scrollbar-thumb { background: #333; border-radius: 3px; }
    </style>
    @livewireStyles
</head>
<body class="bg-[#0d2137] min-h-screen text-white">

    <!-- Navbar -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-black/80 backdrop-blur-md border-b border-white/5">
        <div class="max-w-6xl mx-auto px-4 flex items-center justify-between h-16">
            <a href="/" class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg overflow-hidden flex items-center justify-center"
                     style="background: {{ $accentColor }}20; border: 1px solid {{ $accentColor }}40;">
                    @if($logoPath)
                        <img src="{{ Storage::url($logoPath) }}" alt="{{ $firmName }}" class="w-full h-full object-contain p-1">
                    @else
                        <svg class="w-5 h-5" style="color:{{ $accentColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    @endif
                </div>
                <span class="font-bold text-white text-lg tracking-tight">{{ $firmName }}</span>
            </a>

            <div class="hidden md:flex items-center gap-6 text-sm text-gray-400">
                <a href="#hizmetler" class="hover:text-white transition-colors">Hizmetler</a>
                <a href="#neden-biz" class="hover:text-white transition-colors">Neden Biz?</a>
                @if($phone)
                <a href="tel:{{ $phone }}" class="flex items-center gap-1.5 hover:text-white transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    {{ $phone }}
                </a>
                @endif
            </div>

            <a href="#randevu" class="btn-accent text-black text-sm font-semibold px-5 py-2 rounded-lg">
                Randevu Al
            </a>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="pt-16">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-black border-t border-white/5 mt-16">
        <div class="max-w-6xl mx-auto px-4 py-10">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center"
                             style="background:{{ $accentColor }}20; border:1px solid {{ $accentColor }}40;">
                            <svg class="w-4 h-4" style="color:{{ $accentColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <span class="font-bold text-white">{{ $firmName }}</span>
                    </div>
                    <p class="text-sm text-gray-500">Profesyonel oto yıkama, PPF kaplama ve detailing hizmetleri.</p>
                </div>
                <div>
                    <h4 class="font-semibold text-white mb-3 text-sm uppercase tracking-wider">Hizmetler</h4>
                    <ul class="space-y-1.5">
                        @foreach(\App\Models\Service::where('is_active', true)->limit(4)->get() as $s)
                            <li class="text-sm text-gray-500 hover:text-gray-300 transition-colors">{{ $s->name }}</li>
                        @endforeach
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-white mb-3 text-sm uppercase tracking-wider">İletişim</h4>
                    @if($phone)
                    <a href="tel:{{ $phone }}" class="flex items-center gap-2 text-sm text-gray-500 hover:text-cyan-400 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        {{ $phone }}
                    </a>
                    @endif
                    <p class="text-sm text-gray-600 mt-2">7/24 Online Randevu</p>
                </div>
            </div>
            <div class="border-t border-white/5 mt-8 pt-6 text-center text-xs text-gray-600">
                &copy; {{ date('Y') }} {{ $firmName }}. Tüm hakları saklıdır.
            </div>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
