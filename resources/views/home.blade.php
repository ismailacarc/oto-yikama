<x-layouts.app>
@php
    $accentColor = \App\Models\Setting::get('accent_color', '#06b6d4');
    $firmName = \App\Models\Setting::get('salon_name', 'AutoDetail Pro');
    $services = \App\Models\Service::where('is_active', true)->get();
@endphp

<style>
    .hero-section {
        background: linear-gradient(135deg, #0d2137 0%, #0f2d3f 50%, #0d2137 100%);
        min-height: 88vh;
        position: relative;
        overflow: hidden;
    }
    .hero-section::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 700px;
        height: 700px;
        background: radial-gradient(circle, rgba(6,182,212,0.08) 0%, transparent 70%);
        pointer-events: none;
    }
    .hero-section::after {
        content: '';
        position: absolute;
        bottom: -20%;
        left: -10%;
        width: 500px;
        height: 500px;
        background: radial-gradient(circle, rgba(14,116,144,0.06) 0%, transparent 70%);
        pointer-events: none;
    }
    .service-card {
        background: linear-gradient(145deg, rgba(255,255,255,0.05), rgba(255,255,255,0.02));
        border: 1px solid rgba(255,255,255,0.08);
        transition: all 0.3s ease;
    }
    .service-card:hover {
        border-color: rgba(6,182,212,0.35);
        transform: translateY(-4px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.3), 0 0 0 1px rgba(6,182,212,0.1);
    }
    .stat-card {
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.07);
    }
    .feature-icon {
        background: linear-gradient(135deg, rgba(6,182,212,0.15), rgba(6,182,212,0.05));
        border: 1px solid rgba(6,182,212,0.2);
    }
    .badge {
        background: rgba(6,182,212,0.1);
        border: 1px solid rgba(6,182,212,0.25);
        color: #06b6d4;
    }
    .cta-section {
        background: linear-gradient(135deg, #0d2137 0%, #0f2d3f 100%);
        border-top: 1px solid rgba(255,255,255,0.06);
        border-bottom: 1px solid rgba(255,255,255,0.06);
    }
    .grid-bg {
        background-image:
            linear-gradient(rgba(255,255,255,0.025) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255,255,255,0.025) 1px, transparent 1px);
        background-size: 40px 40px;
    }
</style>

<!-- HERO -->
<section class="hero-section grid-bg flex items-center">
    <div class="max-w-6xl mx-auto px-4 py-20 w-full relative z-10">
        <div class="grid md:grid-cols-2 gap-12 items-center">

            <!-- Sol: Metin -->
            <div class="fade-up">
                <div class="badge inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-semibold mb-6">
                    <span class="w-1.5 h-1.5 rounded-full bg-cyan-400 animate-pulse"></span>
                    Online Randevu Sistemi
                </div>

                <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-white leading-tight mb-5">
                    Aracınız <br>
                    <span style="color:{{ $accentColor }}">Profesyonel</span><br>
                    Ellerde
                </h1>

                <p class="text-gray-400 text-lg leading-relaxed mb-8 max-w-md">
                    PPF kaplama, seramik kaplama, detailing ve oto yıkama hizmetleri.
                    Online randevu ile zamanınızı verimli kullanın.
                </p>

                <div class="flex flex-wrap gap-3">
                    <a href="/randevu" class="btn-accent inline-flex items-center gap-2 text-black font-bold px-7 py-3.5 rounded-xl text-base">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Randevu Al
                    </a>
                    <a href="#hizmetler" class="inline-flex items-center gap-2 text-gray-300 font-semibold px-7 py-3.5 rounded-xl text-base border border-white/10 hover:border-white/20 hover:text-white transition-all">
                        Hizmetlere Bak
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </a>
                </div>

                <!-- Badges -->
                <div class="flex flex-wrap gap-4 mt-10">
                    <div class="flex items-center gap-2 text-sm text-gray-400">
                        <svg class="w-4 h-4 text-cyan-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        7/24 Online
                    </div>
                    <div class="flex items-center gap-2 text-sm text-gray-400">
                        <svg class="w-4 h-4 text-cyan-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        Anında Onay
                    </div>
                    <div class="flex items-center gap-2 text-sm text-gray-400">
                        <svg class="w-4 h-4 text-cyan-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        SMS Bildirim
                    </div>
                </div>
            </div>

            <!-- Sağ: İstatistikler + Araç Görseli -->
            <div class="fade-up-delay hidden md:flex flex-col gap-4">
                <div class="grid grid-cols-2 gap-4">
                    <div class="stat-card rounded-2xl p-5">
                        <p class="text-3xl font-black text-white mb-1">500+</p>
                        <p class="text-sm text-gray-500">Mutlu Müşteri</p>
                        <div class="flex mt-2">
                            @for($i=0;$i<5;$i++)
                            <svg class="w-3.5 h-3.5 text-cyan-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            @endfor
                        </div>
                    </div>
                    <div class="stat-card rounded-2xl p-5">
                        <p class="text-3xl font-black text-white mb-1">5 Yıl</p>
                        <p class="text-sm text-gray-500">Deneyim</p>
                        <p class="text-xs text-cyan-400 mt-2 font-medium">Sektörde lider</p>
                    </div>
                </div>

                <!-- Araç Detailing İllüstrasyon Kartı -->
                <div class="stat-card rounded-2xl p-6 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 opacity-5">
                        <svg viewBox="0 0 100 100" fill="white">
                            <path d="M10,60 L20,35 Q25,25 35,25 L65,25 Q75,25 80,35 L90,60 L90,70 Q90,75 85,75 L75,75 L75,70 L25,70 L25,75 L15,75 Q10,75 10,70 Z"/>
                            <circle cx="28" cy="72" r="8" fill="white"/>
                            <circle cx="72" cy="72" r="8" fill="white"/>
                        </svg>
                    </div>
                    <div class="flex items-center gap-3 mb-4">
                        <div class="feature-icon w-10 h-10 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-white font-semibold text-sm">PPF Koruma</p>
                            <p class="text-gray-500 text-xs">Boya Koruma Filmi</p>
                        </div>
                    </div>
                    <div class="w-full bg-gray-800 rounded-full h-1.5 mb-1">
                        <div class="h-1.5 rounded-full" style="width:92%; background:{{ $accentColor }}"></div>
                    </div>
                    <p class="text-xs text-gray-600">%92 müşteri memnuniyeti</p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="stat-card rounded-2xl p-5">
                        <div class="feature-icon w-8 h-8 rounded-lg flex items-center justify-center mb-3">
                            <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <p class="text-white font-semibold text-sm">Hızlı Servis</p>
                        <p class="text-xs text-gray-500 mt-1">Zamanında teslimat</p>
                    </div>
                    <div class="stat-card rounded-2xl p-5">
                        <div class="feature-icon w-8 h-8 rounded-lg flex items-center justify-center mb-3">
                            <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <p class="text-white font-semibold text-sm">Pro Ekipman</p>
                        <p class="text-xs text-gray-500 mt-1">Son teknoloji</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- HİZMETLER -->
<section id="hizmetler" class="py-20" style="background:#0c1e30">
    <div class="max-w-6xl mx-auto px-4">
        <div class="text-center mb-14">
            <span class="badge inline-block px-3 py-1 rounded-full text-xs font-semibold mb-4">Hizmetlerimiz</span>
            <h2 class="text-3xl md:text-4xl font-black text-white mb-4">
                Aracınız İçin <span style="color:{{ $accentColor }}">Her Şey</span>
            </h2>
            <p class="text-gray-500 max-w-xl mx-auto">
                Profesyonel ekibimiz ve son teknoloji ekipmanlarımızla aracınıza en iyi hizmeti sunuyoruz.
            </p>
        </div>

        @if($services->count())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 mb-8">
            @foreach($services as $service)
            <div class="service-card rounded-2xl p-6">
                <div class="flex items-start justify-between mb-4">
                    <div class="feature-icon w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    @if($service->duration)
                    <span class="text-xs text-gray-600 bg-white/5 px-2 py-1 rounded-lg">
                        {{ $service->duration }} dk
                    </span>
                    @endif
                </div>
                <h3 class="text-white font-bold text-base mb-1.5">{{ $service->name }}</h3>
                @if($service->description)
                <p class="text-gray-500 text-sm leading-relaxed mb-4">{{ Str::limit($service->description, 80) }}</p>
                @endif
                <div class="flex items-center justify-between pt-4 border-t border-white/5">
                    <span class="font-black text-lg" style="color:{{ $accentColor }}">
                        {{ number_format($service->price, 0, ',', '.') }} ₺
                    </span>
                    <a href="/randevu" class="text-xs font-semibold text-gray-400 hover:text-cyan-400 transition-colors flex items-center gap-1">
                        Randevu Al
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <!-- Örnek hizmetler (seed olmadıysa) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 mb-8">
            @foreach([
                ['PPF Kaplama', 'Boyama koruma filmi ile aracınızı çizik ve darbelere karşı koruyun.', '8.500', '180'],
                ['Seramik Kaplama', 'Nano seramik kaplama ile uzun süreli parlaklık ve koruma.', '4.500', '240'],
                ['Detailing', 'İç ve dış detaylı temizlik, cila ve koruma işlemleri.', '1.800', '120'],
                ['Dış Yıkama', 'El yıkama, kurulama ve ön koruma uygulaması.', '350', '45'],
                ['İç Temizlik', 'Derin iç temizlik, ozon uygulaması ve koku giderme.', '650', '90'],
                ['Farlar Parlatma', 'Sararmış ve matlaşmış farların temizlenmesi ve korunması.', '450', '60'],
            ] as $s)
            <div class="service-card rounded-2xl p-6">
                <div class="flex items-start justify-between mb-4">
                    <div class="feature-icon w-11 h-11 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <span class="text-xs text-gray-600 bg-white/5 px-2 py-1 rounded-lg">{{ $s[3] }} dk</span>
                </div>
                <h3 class="text-white font-bold text-base mb-1.5">{{ $s[0] }}</h3>
                <p class="text-gray-500 text-sm leading-relaxed mb-4">{{ $s[1] }}</p>
                <div class="flex items-center justify-between pt-4 border-t border-white/5">
                    <span class="font-black text-lg" style="color:{{ $accentColor }}">{{ $s[2] }} ₺</span>
                    <a href="/randevu" class="text-xs font-semibold text-gray-400 hover:text-cyan-400 transition-colors flex items-center gap-1">
                        Randevu Al <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        <div class="text-center">
            <a href="/randevu" class="btn-accent inline-flex items-center gap-2 text-black font-bold px-8 py-4 rounded-xl text-base">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Hemen Randevu Al
            </a>
        </div>
    </div>
</section>

<!-- NEDEN BİZ -->
<section id="neden-biz" class="py-20" style="background:#0a1929">
    <div class="max-w-6xl mx-auto px-4">
        <div class="text-center mb-14">
            <span class="badge inline-block px-3 py-1 rounded-full text-xs font-semibold mb-4">Neden Biz?</span>
            <h2 class="text-3xl md:text-4xl font-black text-white mb-4">Fark Yaratan <span style="color:{{ $accentColor }}">Detaylar</span></h2>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-5">
            @foreach([
                ['Sertifikalı Usta', 'Alanında uzman, sertifikalı teknisyenler.', 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'],
                ['Orijinal Ürünler', '3M, Avery Dennison, XPEL gibi premium markalar.', 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
                ['Garanti', 'Tüm işlemlerimizde yazılı garanti veriyoruz.', 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                ['Online Takip', 'Randevunuzu online oluşturun, SMS ile takip edin.', 'M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z'],
            ] as $f)
            <div class="stat-card rounded-2xl p-6 text-center hover:border-amber-500/20 transition-all duration-300">
                <div class="feature-icon w-12 h-12 rounded-xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $f[2] }}"/>
                    </svg>
                </div>
                <h3 class="text-white font-bold mb-2">{{ $f[0] }}</h3>
                <p class="text-gray-500 text-sm leading-relaxed">{{ $f[1] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- CTA -->
<section class="cta-section py-20">
    <div class="max-w-3xl mx-auto px-4 text-center">
        <h2 class="text-3xl md:text-4xl font-black text-white mb-4">
            Aracınız için <span style="color:{{ $accentColor }}">randevu</span> alın
        </h2>
        <p class="text-gray-400 mb-8 text-lg">
            Dakikalar içinde randevunuzu oluşturun. Onay SMS ile gelsin.
        </p>
        <a href="/randevu" class="btn-accent inline-flex items-center gap-3 text-black font-black px-10 py-4 rounded-2xl text-lg">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            Randevu Al — Ücretsiz
        </a>
        <p class="text-gray-600 text-sm mt-4">Kayıt gerektirmez • Anında onay • 7/24 açık</p>
    </div>
</section>
</x-layouts.app>
