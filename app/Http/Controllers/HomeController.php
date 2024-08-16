<?php

namespace App\Http\Controllers;

use App\Models\Divisi;
use App\Models\timeLine;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    //
    public function formDaftar1(){
        $cek = timeLine::find(1);
        $pembukaan = Carbon::parse($cek->waktu_mulai)->format('Y-m-d');
        $penutupan = Carbon::parse($cek->waktu_berakhir)->format('Y-m-d');
        $status = $cek->status;
        $currentDate = Carbon::now()->format('Y-m-d');
        $dtDivisi = Divisi::all();
        if ($currentDate < $pembukaan) {
            // return redirect(route('home'))->with('error', 'pendaftaran dibuka tanggal : '.$cek->waktu_mulai .'-'. $cek->waktu_berakhir);
            return redirect()->route('home', compact('status'))->with('error', 'pendaftaran dibuka tanggal : '.$cek->waktu_mulai .' hingga '. $cek->waktu_berakhir);
        } elseif ($currentDate > $penutupan) {
            //return redirect(route('home'))->with('error', 'pendaftaran ditutup tanggal : '.$cek->waktu_berakhir);
            return redirect()->route('home')->with('error', 'pendaftaran ditutup tanggal : '.$cek->waktu_berakhir);
        } 
        
        elseif ($currentDate >= $pembukaan && $currentDate <= $penutupan && $status == 1) {
            return view('pendaftaran.create', compact('dtDivisi'))->with('success', 'selamat mendaftar');
        }
        else if($currentDate >= $pembukaan && $currentDate <= $penutupan && $status == 0){
            return redirect()->route('home')->with('error', 'pendaftaran di non-aktifkan ');
        }
    }
    public function formDaftar2(){
        $cek = timeLine::find(2);
        $pembukaan = Carbon::parse($cek->waktu_mulai)->format('Y-m-d');
        $penutupan = Carbon::parse($cek->waktu_berakhir)->format('Y-m-d');
        $status = $cek->status;
        $currentDate = Carbon::now()->format('Y-m-d');
        $dtDivisi = Divisi::all();
        if ($currentDate < $pembukaan) {
            // return redirect(route('home'))->with('error', 'pendaftaran dibuka tanggal : '.$cek->waktu_mulai .'-'. $cek->waktu_berakhir);
            return redirect()->route('home', compact('status'))->with('error', 'pendaftaran dibuka tanggal : '.$cek->waktu_mulai .' hingga '. $cek->waktu_berakhir);
        } elseif ($currentDate > $penutupan) {
            //return redirect(route('home'))->with('error', 'pendaftaran ditutup tanggal : '.$cek->waktu_berakhir);
            return redirect()->route('home')->with('error', 'pendaftaran ditutup tanggal : '.$cek->waktu_berakhir);
        } 
        
        elseif ($currentDate >= $pembukaan && $currentDate <= $penutupan && $status == 1) {
            return view('pendaftaran.create', compact('dtDivisi'))->with('success', 'selamat mendaftar');
        }
        else if($currentDate >= $pembukaan && $currentDate <= $penutupan && $status == 0){
            return redirect()->route('home')->with('error', 'pendaftaran di non-aktifkan ');
        }
    }
}
