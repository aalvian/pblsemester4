<?php

namespace App\Http\Controllers;

use App\Models\Divisi;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PresensiController extends Controller
{
    //
    public function index(){
        $user = Auth::user();
        $pendaftar = Pendaftaran::where('nim', $user->nim)->first();
        if ($pendaftar && $pendaftar->divisi_1 && $pendaftar->divisi_2) {
            return view('presensi.index', compact('pendaftar'));        
        }  
             
    }

}
