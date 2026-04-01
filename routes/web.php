<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Livewire\Admin\Login;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\Randevular;
use App\Livewire\Admin\Takvim;
use App\Livewire\Admin\MaliBilgiler;
use App\Livewire\Admin\Uyeler;
use App\Livewire\Admin\Islemler;
use App\Livewire\Admin\PersonelYonetimi;
use App\Livewire\Admin\Ayarlar;
use App\Livewire\Admin\Yonetim;
use App\Livewire\Admin\GelirGider;
use App\Livewire\Admin\Urunler;
use App\Livewire\Admin\IsEmirleri;
use App\Livewire\Admin\Hizmetler;
use App\Livewire\Admin\Odemeler;
use App\Livewire\Admin\Raporlar;
use App\Http\Controllers\PdfController;
use App\Livewire\Frontend\RandevuAl;
use App\Livewire\Frontend\RandevuBasarili;

// Frontend
Route::get('/', function () { return view('home'); });
Route::get('/randevu', RandevuAl::class);
Route::get('/randevu/basarili/{appointment}', RandevuBasarili::class);

// Auth
Route::get('/admin/login', Login::class)->name('login');
Route::post('/logout', function () {
    Auth::logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect('/admin/login');
})->name('logout');

// Admin
Route::middleware('auth')->prefix('admin')->group(function () {
    Route::get('/', Dashboard::class);
    Route::get('/randevular', Randevular::class);
    Route::get('/takvim', Takvim::class);
    Route::get('/mali-bilgiler', MaliBilgiler::class);
    Route::get('/gelir-gider', GelirGider::class);
    Route::get('/uyeler', Uyeler::class);
    Route::get('/urunler', Urunler::class);
    Route::get('/hizmetler', Hizmetler::class);
    Route::get('/is-emirleri', IsEmirleri::class);
    Route::get('/odemeler', Odemeler::class);
    Route::get('/raporlar', Raporlar::class);
    Route::get('/is-emri/{id}/pdf', [PdfController::class, 'isEmri'])->name('pdf.is-emri');
    Route::get('/rapor/pdf', [PdfController::class, 'rapor'])->name('pdf.rapor');
    Route::get('/islemler', Islemler::class);
    Route::get('/personel', PersonelYonetimi::class);
    Route::get('/ayarlar', Ayarlar::class);
    Route::get('/yonetim', Yonetim::class);
});