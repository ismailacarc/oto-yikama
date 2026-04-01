<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class Yonetim extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $role = 'personel';

    public function createUser()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'role' => 'required|in:super,personel',
        ]);

        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role' => $this->role,
        ]);

        $this->reset(['name', 'email', 'password', 'password_confirmation', 'role']);
        $this->role = 'personel';
        session()->flash('success', 'Kullanıcı oluşturuldu.');
    }

    public function updateRole(int $id, string $role)
    {
        User::findOrFail($id)->update(['role' => $role]);
    }

    public function deleteUser(int $id)
    {
        $user = User::findOrFail($id);
        if ($user->id === auth()->id()) {
            session()->flash('error', 'Kendinizi silemezsiniz.');
            return;
        }
        $user->delete();
    }

    public function render()
    {
        return view('livewire.admin.yonetim', [
            'users' => User::all(),
        ])->layout('components.layouts.admin');
    }
}
