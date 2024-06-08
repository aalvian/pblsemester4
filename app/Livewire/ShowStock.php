<?php

namespace App\Livewire;
use App\Models\Alat;

use Livewire\Component;

class ShowStock extends Component
{

    public $alat; // List of alat
    public $selectedAlat = null; // Selected alat
    public $stock = 0; // Stock of selected alat

    public function mount()
    {
        $this->alat = Alat::all(); // Load all alat
    }

    public function updatedSelectedAlat($namaBarang)
    {
        $this->stock = Alat::where('nama_barang', $namaBarang)->first()->stok;
    }
    public function render()
    {
        return view('livewire.show-stock');
    }
}
