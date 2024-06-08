


<div>
    {{-- Success is as dangerous as failure. --}}
    <div>
    <div class="form-group">
        <label for="nama_barang">Nama Alat</label>
        <select wire:model="selectedAlat" name="nama_barang" class="form-control">
            <option value="">-- Select Alat --</option>
            @foreach ($alat as $item)
                <option value="{{ $item->nama_barang }}">{{ $item->nama_barang }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="jml_barang">Jumlah Barang</label>
        <input type="number" name="jml_barang" class="form-control" required>
    </div>

    @if ($selectedAlat)
        <div class="form-group">
            <label for="stock">Stok Tersedia</label>
            <input type="text" name="stock" class="form-control" value="{{ $stock }}" readonly>
        </div>
    @endif
</div>

   
</div>
