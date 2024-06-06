<?php

namespace App\Http\Controllers;

use App\Mail\sendMail;
use App\Models\Divisi;
use App\Models\Pendaftaran;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;


class PendaftaranController extends Controller
{
    public function index()
    {
        $dtPendaftaran = Pendaftaran::all();
        return view ('pendaftaran.index', compact('dtPendaftaran'));
    }

    public function formDaftar(){
        //$pendaftar = Pendaftaran::all();
        return view('pendaftaran.create');
    }
    public function detail($id)
    {
        $id = decrypt($id);
        $detail = Pendaftaran::where('id', $id)->first();
        return view('pendaftaran.detail', compact('detail'));
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
        ]);

        return redirect()->route('login')->with('succes', 'Kamu Berhasil mendaftar');

        
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
            'title' => 'Mail from Laravel',
            'body' => 'This is for testing email using smtp'
        ];

        $Url = url('registered/akun/'.$token);

        $user->update([
            'remember_token' => $token,
    ]);

        Mail::to($pendaftar->email)->send(new sendMail($details, $Url));
        return redirect('pendaftaran')->with('toast_success', 'Pendaftar diterima');
    }

    public function updatetolak($id)
    {
        $user = Auth::user()->id;
        $pendaftar = Pendaftaran::find($id);
        $pendaftar->update([
            'status' => "tolak",
        ]);

        return redirect(route('pendaftaran'))->with('toast_success', 'Pendaftar ditolak');
    }

    public function view($token){
        return view ('coba',['token' => $token]);
    }
}
