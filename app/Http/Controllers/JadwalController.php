<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use App\Models\Divisi;
use App\Models\Jadwal;
use Illuminate\Http\Request;

class JadwalController extends Controller
{

    public function index()
    {
        $dtJadwal = Jadwal::all();
        $user = auth()->user();
        $logName = $user->name;
        activity()->inLog($logName)->log('mengakses jadwal');
        return view ('jadwal.index', compact('dtJadwal'));
    }


    public function create() // relasi untuk menampilkan divisi
    {
        $divisis = Divisi::all();
        return view ('jadwal.create', compact('divisis'));
    }


    public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'hari' => 'required',
        'waktu_mulai'  => 'required|date_format:H:i',
        'waktu_selesai'  => 'required|date_format:H:i|after:waktu_mulai',
    ], [
        'waktu_selesai.after' => 'Waktu selesai harus setelah waktu mulai.',
    ]);

    // Tambahkan custom validation untuk memeriksa waktu yang sama
    // $validator->after(function ($validator) use ($request) {
    //     if ($request->waktu_mulai == $request->waktu_selesai) {
    //         $validator->errors()->add('waktu_selesai', 'Waktu selesai tidak boleh sama dengan waktu mulai.');
    //     }
    // });

    if ($validator->fails()) {
        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    }

    Jadwal::create([
        'hari' => $request->hari,
        'waktu_mulai' => $request->waktu_mulai,
        'waktu_selesai' => $request->waktu_selesai,
        'divisi_id' => $request->divisi_id,
    ]);

    $user = auth()->user();
    $logName = $user->name;
    activity()->inLog($logName)->log('create jadwal');
    return redirect('jadwal')->with('toast_success', 'Data Berhasil Disimpan');
}



    public function edit($id)
    {
        $id = decrypt($id);
        $jadwals = Jadwal::findOrFail($id);
        $divisis = Divisi::all();
        return view('jadwal.edit', compact('jadwals', 'divisis'));
    }


    public function update(Request $request, string $id)
    {
        $jadwals = Jadwal::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'hari' => 'required',
            'waktu_mulai'  => 'required|date_format:H:i',
            'waktu_selesai'  => 'required|date_format:H:i|after:waktu_mulai',
        ], [
            'waktu_selesai.after' => 'Waktu selesai harus setelah waktu mulai.',
        ]);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        $jadwals->update($request->all());
        $user = auth()->user();
        $logName = $user->name;
        activity()->inLog($logName)->log('mengedit jadwal');
        return redirect('jadwal')->with('toast_success', 'Data Berhasil Diubah');
    }


    public function destroy(string $id)
    {
        $jadwals = Jadwal::findOrFail($id);
        $jadwals->delete();
        $user = auth()->user();
        $logName = $user->name;
        activity()->inLog($logName)->log('menghapus jadwal');
        return back()->with('toast_success', 'Data Berhasil Dihapus');
    }
}
