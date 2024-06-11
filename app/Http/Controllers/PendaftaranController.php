<?php

namespace App\Http\Controllers;

use App\Mail\sendEmail;
use App\Mail\sendTolak as tolak;
use App\Models\Pendaftaran;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class PendaftaranController extends Controller
{
    public function index()
    {
        $dtPendaftaran = Pendaftaran::all();
        return view ('pendaftaran.index', compact('dtPendaftaran'));
    }

    public function formDaftar(){
        return view('pendaftaran.create');
    }

    public function store(Request $request){
        $this->validate($request, [
            'nama'=>'required',
            'nim'=>'required',
            'prodi'=>'required',
            'email'=>'required',
            'no_telp'=>'required',
            'cv'=>'nullable',
            'semester'=>'required',
            'divisi_1'=>'required',
            'divisi_2'=>'nullable',
        ]);

        Pendaftaran::create([
            'nama' => $request->nama,
            'nim'=> $request->nim,
            'prodi'=>$request->prodi,
            'email'=>$request->email,
            'no_telp'=>$request->no_telp,
            'cv'=>$request->cv,
            'semester'=>$request->semester,
            'divisi_1'=>$request->divisi_1,
            'divisi_2'=>$request->divisi_2,

        ]);
        User::create([
            'name'=>$request->nama,
            'email'=>$request->email,
            'nim'=> $request->nim,
            'prodi'=>$request->prodi,
            'email'=>$request->email,
        ]);
        
        activity()->withProperties([$request->nama, $request->nim])->log($request->nama.'melakukan pendaftaran pada divisi'.$request->divisi_1.'dan'.$request->divisi_2);
        return redirect()->route('login')->with('succes', 'Kamu Berhasil mendaftar');

        
    }

    public function detail($id)
    {
        $detail = Pendaftaran::where('id', $id)->first();
        return view('pendaftaran.detail', compact('detail'));
    }


    // public function edit(string $id)
    // {
    //     $pendaftar = Pendaftaran::findOrFail($id);
    //     return view('pendaftaran.edit', compact('pendaftar'));
    // }


    // public function update(Request $request, string $id)
    // {
    //     $pendaftar = Pendaftaran::findOrFail($id);
    //     $pendaftar->update($request->all());
    //     return redirect()->route('detail-terima')->with('toast_success', 'Data Berhasil Diubah');
    // }


    public function terima()
    {
        $pendaftar = Pendaftaran::all();
        return view('pendaftaran.terima', compact('pendaftar'));
    }

    public function view($token){
        return view ('auth.updatepass',['token' => $token]);
    }
    public function tolak()
    {
        $pendaftar = Pendaftaran::all();
        return view('pendaftaran.tolak', compact('pendaftar'));
    }

    public function updateterima($id)
    {
        // $user = User::where()
        $pendaftar = Pendaftaran::find($id);
        $user = User::where('email', $pendaftar->email)->first();

        $pendaftar->update([
            'status' => "terima",
        ]);

        $token = Str::random(20);

        $details = [
            'title' => 'Diterima',
            'body' => 'selamat anda diterima untuk bergabung di UKM Olahraga'
        ];

        $Url = url('registered/akun/'.$token);

        $user->update([
            'remember_token' => $token,
    ]);

        Mail::to($pendaftar->email)->send(new sendEmail($details, $Url));
        $user = auth()->user();
        $logName = $user->name;
        activity()->withProperties([$pendaftar->nama, $pendaftar->divisi_1, $pendaftar->divisi_2])->inLog($logName)->log($logName.'menerima'.$pendaftar->nama.'menjadi anggota');
        return redirect('pendaftaran')->with('toast_success', 'Pendaftar diterima');
    }

    public function updatetolak($id)
    {
        $pendaftar = Pendaftaran::find($id);
        $user = User::where('email', $pendaftar->email)->first();
        $pendaftar->update([
            'status' => "tolak",
        ]);

        $details = [
            'title' => 'wkwk ditolak',
            'body' => 'cieee ditolakk..'
        ];
        Mail::to($pendaftar->email)->send(new tolak($details));
        $user = auth()->user();
        $logName = $user->name;
        activity()->withProperties([$pendaftar->nama, $pendaftar->divisi_1, $pendaftar->divisi_2])->inLog($logName)->log($logName.'menolak'.$pendaftar->nama.'menjadi anggota');
        return redirect('pendaftaran')->with('toast_success', 'Pendaftar ditolak');
        return redirect(route('pendaftaran'))->with('toast_success', 'Pendaftar ditolak');
    }
}
