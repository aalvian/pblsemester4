<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BuatAkunController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dtPengurus = Pendaftaran::all();

        return view('buatAkun.index', compact('dtPengurus'));
    }

    public function detail($id){
        $detail = Pendaftaran::where('id', $id)->first();
        return view('buatAkun.detail', compact('detail'));
    }

    public function update(Request $request, $id)
    {
        $pendaftar = User::find($id);
        // dd($pendaftar);
        $pendaftar->assignRole('pengurus');

        $pendaftar = Pendaftaran::find($id);
        $pendaftar->update([
            'jabatan_2' => "pengurus",
        ]);
        $user = auth()->user();
        $logName = $user->name;
        activity()->withProperties($pendaftar)->inLog($logName)->log('membuat akun pengurus pada user '.$pendaftar->nama);
        return redirect()->route('pengurus')->withSuccess('Jabatan Berhasil Diubah.');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $dtAnggota = Pendaftaran::all();
        return view('buatAkun.create', compact('dtAnggota'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pengurus = User::findOrFail($id);
        $pengurus->removeRole('pengurus');

        $pendaftar = Pendaftaran::find($id);
        $pendaftar->update([
            'jabatan_2' => NULL,
        ]);
        return back()->with('toast_success', 'Data Berhasil Dihapus');
    }
}
