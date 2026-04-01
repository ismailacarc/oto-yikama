<div>
    <div class="max-w-lg mx-auto">
        <!-- Success Alert -->
        <div class="bg-green-50 border border-green-200 text-green-700 px-5 py-4 rounded-xl mb-6 flex items-center gap-3">
            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            </div>
            <div>
                <p class="font-medium">Randevu talebiniz alindi.</p>
                <p class="text-sm text-green-600">Onaylandiginda size bilgi verilecektir.</p>
            </div>
        </div>

        <!-- Main Card -->
        <div class="bg-white rounded-2xl shadow-lg p-8 text-center border border-gray-100">
            <!-- Animated Check -->
            <div class="relative inline-block mb-4">
                <div class="w-20 h-20 bg-gradient-to-br from-green-400 to-emerald-500 rounded-full flex items-center justify-center shadow-lg shadow-green-200">
                    <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                </div>
                <div class="absolute -top-1 -right-1 w-6 h-6 bg-green-200 rounded-full flex items-center justify-center">
                    <svg class="w-3 h-3 text-green-700" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                </div>
            </div>

            <h2 class="text-2xl font-bold text-gray-900 mb-1">Randevunuz olusturuldu!</h2>
            <p class="text-gray-400 mb-6 flex items-center justify-center gap-2">
                <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7z" clip-rule="evenodd"/></svg>
                WhatsApp ile bilgilendirme gonderildi
            </p>

            <p class="text-gray-600 mb-6"><strong>{{ $appointment->customer->name }}</strong>, randevu detaylariniz:</p>

            <!-- Appointment Details -->
            <div class="bg-gradient-to-br from-gray-50 to-green-50/30 rounded-xl p-5 text-left mb-6 border border-gray-100">
                <div class="space-y-4">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wider">TARIH & SAAT</p>
                            <p class="font-semibold text-gray-900">{{ $appointment->appointment_date->translatedFormat('d F Y, l') }}</p>
                            <p class="text-green-600 font-bold text-lg">{{ $appointment->appointment_date->format('H:i') }}</p>
                        </div>
                    </div>

                    <div class="border-t border-gray-100"></div>

                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wider">ISLEMLER</p>
                            <p class="font-semibold text-gray-900">{{ $appointment->services->pluck('name')->join(', ') }}</p>
                        </div>
                    </div>

                    <div class="border-t border-gray-100"></div>

                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wider">TOPLAM</p>
                            <p class="font-bold text-2xl text-green-600">{{ number_format($appointment->total_price, 0, ',', '.') }} &#8378;</p>
                        </div>
                    </div>
                </div>
            </div>

            <a href="/" class="inline-flex items-center gap-2 bg-green-600 text-white px-8 py-3.5 rounded-xl hover:bg-green-700 font-semibold transition-all shadow-lg shadow-green-200 hover:shadow-xl">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Yeni randevu al
            </a>
        </div>
    </div>
</div>
