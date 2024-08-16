<?php

namespace App\Http\Controllers;

use App\Models\Divisi;
use App\Models\Pendaftaran;
use App\Models\User;
use Illuminate\Http\Request;

class DivisiController extends Controller
{

    public function index()
    {
        $dtDivisi = Divisi::all();
        $user = auth()->user();
        $logName = $user->name;
        activity()->inLog($logName)->log('mengakses divisi');
        return view('divisi.index', compact('dtDivisi'));
    }


    public function create()
    {
        return view('divisi.create');
    }


    public function store(Request $request)
    {
        $this->validate($request, [
            'nama' => 'required'
        ]);

        Divisi::create([
            'nama' => $request->nama,
        ]);

        $user = auth()->user();
        $logName = $user->name;
        activity()->inLog($logName)->log('menambahkan divisi');
        return redirect('divisi')->with('toast_success', 'Data Berhasil Disimpan');
    }


    public function edit($id)
    {
        $id = decrypt($id);
        $divisis = Divisi::findOrFail($id);
        return view('divisi.edit', compact('divisis'));
    }


    public function update(Request $request, string $id)
    {
        $divisis = Divisi::findOrFail($id);
        $divisis->update($request->all());
        $user = auth()->user();
        $logName = $user->name;
        activity()->inLog($logName)->log('mengedit divisi');
        return redirect('divisi')->with('toast_success', 'Data Berhasil Diubah');
    }


    public function destroy(string $id)
    {
        $divisis = Divisi::findOrFail($id);
        $divisis->delete();
        $user = auth()->user();
        $logName = $user->name;
        activity()->inLog($logName)->log('menghapus divisi');
        return back()->with('toast_success', 'Data Berhasil Dihapus');
    }

    public function viewAnggota($id)
{
    // Find the division by its ID
    $id = decrypt($id);
    $divisi = Divisi::findOrFail($id);

    // Fetch applicants who have selected this division
    $anggota = Pendaftaran::where(function($query) use ($divisi) {
        $query->where('divisi_1', $divisi->nama)
              ->orWhere('divisi_2', $divisi->nama);
    })
    ->where('status', 'terima')
    ->get();
    foreach ($anggota as $item) {
        $user = User::where('nim', $item->nim)->first(); // Misalnya menggunakan nim sebagai kunci asing
        if ($user) {
            $item->gambar = $user->gambar;
        }
    }
    return view('divisi.viewAnggota', compact('divisi', 'anggota'));
}
}
