<?php

namespace App\Http\Controllers;

use App\Models\Divisi;
use App\Models\Jadwal;
use Illuminate\Http\Request;

class JadwalController extends Controller
{

    public function index()
    {
        $user = auth()->user();
        $logName = $user->name;
        activity()->inLog($logName)->log('mengakses jadwal');
        $dtJadwal = Jadwal::all();
        return view ('jadwal.index', compact('dtJadwal'));
    }


    public function create() // relasi untuk menampilkan divisi
    {
        $divisis = Divisi::all();
        return view ('jadwal.create', compact('divisis'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'hari' => 'required',
            'waktu_mulai'  => 'required',
            'waktu_selesai'  => 'required',
        ]);

        Jadwal::create([
            'hari' => $request->hari,
            'waktu_mulai' => $request->waktu_mulai,
            'waktu_selesai' => $request->waktu_selesai,
            'divisi_id'=>$request->divisi_id,
        ]);
        $user = auth()->user();
        $logName = $user->name;
        activity()->inLog($logName)->log('create jadwal');
        return redirect('jadwal')->with('toast_success', 'Data Berhasil Disimpan');
    }


    public function edit(string $id)
    {
        $jadwals = Jadwal::findOrFail($id);
        $divisis = Divisi::all();
        return view('jadwal.edit', compact('jadwals', 'divisis'));
    }


    public function update(Request $request, string $id)
    {
        $jadwals = Jadwal::findOrFail($id);
        $jadwals->update($request->all());
        $user = auth()->user();
        $logName = $user->name;
        activity()->inLog($logName)->log('edit jadwal');
        return redirect('jadwal')->with('toast_success', 'Data Berhasil Diubah');
    }


    public function destroy(string $id)
    {
        $jadwals = Jadwal::findOrFail($id);
        $jadwals->delete();
        $user = auth()->user();
        $logName = $user->name;
        activity()->inLog($logName)->log('hapus jadwal');
        return back()->with('toast_success', 'Data Berhasil Dihapus');
    }
}
