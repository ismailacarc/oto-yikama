<?php
namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Service;

class Hizmetler extends Component
{
    use WithPagination;

    public string $search = '';
    public bool   $showForm = false;
    public ?int   $editingId = null;

    public string $name        = '';
    public string $description = '';
    public string $price       = '';
    public bool   $is_active   = true;

    public function updatingSearch() { $this->resetPage(); }

    public function openForm(?int $id = null)
    {
        $this->reset(['editingId','name','description','price']);
        $this->is_active = true;
        if ($id) {
            $s = Service::findOrFail($id);
            $this->editingId   = $s->id;
            $this->name        = $s->name;
            $this->description = $s->description ?? '';
            $this->price       = (string) $s->price;
            $this->is_active   = $s->is_active;
        }
        $this->showForm = true;
    }

    public function save()
    {
        $this->validate([
            'name'  => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ], [
            'name.required'  => 'Hizmet adı zorunludur.',
            'price.required' => 'Fiyat zorunludur.',
        ]);

        $data = [
            'name'        => $this->name,
            'description' => $this->description ?: null,
            'price'       => (float) $this->price,
            'is_active'   => $this->is_active,
        ];

        $this->editingId
            ? Service::findOrFail($this->editingId)->update($data)
            : Service::create($data);

        $this->showForm = false;
        $this->reset(['editingId','name','description','price']);
        $this->is_active = true;
    }

    public function delete(int $id)
    {
        Service::findOrFail($id)->delete();
    }

    public function toggleActive(int $id)
    {
        $s = Service::findOrFail($id);
        $s->update(['is_active' => !$s->is_active]);
    }

    public function render()
    {
        $services = Service::when($this->search, fn($q) =>
            $q->where('name', 'like', '%'.$this->search.'%')
        )->orderBy('name')->paginate(20);

        return view('livewire.admin.hizmetler', compact('services'))
            ->layout('components.layouts.admin');
    }
}
