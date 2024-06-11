<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use Illuminate\Http\Request;

class AlatController extends Controller
{

    public function index()
    {
        $user = auth()->user();
        $logName = $user->name;
        activity()->inLog($logName)->log('membuka alat');
        $dtAlat = Alat::all();
        return view('alat.index', compact('dtAlat'));
    }


    public function create()
    {
        return view('alat.create');

    }


    public function store(Request $request)
    {

        $this->validate($request, [
            'nama_barang' => 'required',
            'stok' => 'required',
            'tggl_masuk' => 'required'
        ]);

        Alat::create([
            'nama_barang' => $request->nama_barang,
            'stok' => $request->stok,
            'tggl_masuk' => $request->tggl_masuk,
        ]);
        $user = auth()->user();
        $logName = $user->name;
        activity()->inLog($logName)->log('menambah alat');
        return redirect('alat')->with('toast_success', 'Data Berhasil Disimpan');
    }


    public function edit(string $id)
    {
        $alat = Alat::findOrFail($id);
        return view('alat.edit', compact('alat'));
    }


    public function update(Request $request, string $id)
    {
        $alat = Alat::findOrFail($id);
        $alat->update($request->all());
        $user = auth()->user();
        $logName = $user->name;
        activity()->inLog($logName)->log('mengedit alat');
        return redirect('alat')->with('toast_success', 'Data Berhasil Diubah');
    }


    public function destroy(string $id)
    {
        $alat = Alat::findOrFail($id);
        $alat->delete();
        $user = auth()->user();
        $logName = $user->name;
        activity()->inLog($logName)->log('menghapus alat');
        return back()->with('toast_success', 'Data Berhasil Dihapus');
    }
}
