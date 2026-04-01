<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Setting;

class Ayarlar extends Component
{
    use WithFileUploads;

    // Firma Bilgileri
    public string $firm_name    = '';
    public string $firm_phone   = '';
    public string $firm_email   = '';
    public string $firm_address = '';
    public string $firm_tax_no  = '';
    public string $firm_tax_office = '';

    // Logo
    public $logo         = null;
    public string $current_logo = '';

    // Uygulama
    public string $currency     = '₺';
    public string $low_stock_default = '5';

    public bool $saved = false;

    public function mount()
    {
        $this->firm_name       = Setting::get('firm_name',       'UFK Garage');
        $this->firm_phone      = Setting::get('firm_phone',      '');
        $this->firm_email      = Setting::get('firm_email',      '');
        $this->firm_address    = Setting::get('firm_address',    '');
        $this->firm_tax_no     = Setting::get('firm_tax_no',     '');
        $this->firm_tax_office = Setting::get('firm_tax_office', '');
        $this->current_logo    = Setting::get('logo',            '');
        $this->currency        = Setting::get('currency',        '₺');
        $this->low_stock_default = Setting::get('low_stock_default', '5');
    }

    public function save()
    {
        $this->validate([
            'firm_name'  => 'required|string|max:100',
            'firm_email' => 'nullable|email|max:100',
            'logo'       => 'nullable|image|max:2048',
        ], [
            'firm_name.required' => 'Firma adı zorunludur.',
            'firm_email.email'   => 'Geçerli bir e-posta giriniz.',
        ]);

        if ($this->logo) {
            $path = $this->logo->store('logos', 'public');
            Setting::set('logo', $path);
            $this->current_logo = $path;
            $this->logo = null;
        }

        Setting::set('firm_name',       $this->firm_name);
        Setting::set('firm_phone',      $this->firm_phone);
        Setting::set('firm_email',      $this->firm_email);
        Setting::set('firm_address',    $this->firm_address);
        Setting::set('firm_tax_no',     $this->firm_tax_no);
        Setting::set('firm_tax_office', $this->firm_tax_office);
        Setting::set('currency',        $this->currency);
        Setting::set('low_stock_default', $this->low_stock_default);

        $this->saved = true;
        $this->dispatch('saved');
    }

    public function removeLogo()
    {
        Setting::set('logo', '');
        $this->current_logo = '';
    }

    public function render()
    {
        return view('livewire.admin.ayarlar')->layout('components.layouts.admin');
    }
}
