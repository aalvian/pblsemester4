<?php

namespace App\Http\Controllers;

use App\Mail\sendEmail;
use App\Mail\sendTolak as tolak;
use App\Models\Pendaftaran;
use App\Models\timeLine;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
class PendaftaranController extends Controller
{
    public function index()
    {
        $dtPendaftaran = Pendaftaran::all();
        
        return view ('pendaftaran.index', compact('dtPendaftaran'));
    }

    
    

    public function store(Request $request) {
        $this->validate($request, [
            'nama' => [
                'required',
                'regex:/^[a-zA-Z\s]+$/'
            ],
            'nim' => 'required',
            'prodi' => 'required',
            'email' => 'required|email',
            'no_telp' => 'required',
            'cv' => 'nullable|mimes:jpeg,png,jpg|max:10240',
            'semester' => 'required',
            'divisi_1' => 'required',
            'divisi_2' => 'nullable',
        ], [
            'regex' => 'tidak boleh memasukkan karakter asing',
            'cv.mimes'=>'cv harus jpeg, png, jpg',
        ]);
    
        if ($request->hasFile('cv')) {
            $image = $request->file('cv');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/cv', $imageName); // Menyimpan gambar ke dalam storage/app/public/cv
        } else {
            $imageName = null; // Tidak ada gambar yang diunggah
        }
        

        $cekDivisi1 = $request->divisi_1;
        $cekDivisi2 = $request->divisi_2;
        if ($cekDivisi1===$cekDivisi2) {
            # code...
            return redirect()->route('home')->with('error', 'divisi tidak boleh sama');
        }

        $cekPendaftar = Pendaftaran::where('nim', $request->nim)
                                    ->orWhere('email', $request->email)
                                    ->first();
    
        $cekUser = User::where('nim', $request->nim)
                        ->orWhere('email', $request->email)
                        ->first();
    
        if ($cekPendaftar || $cekUser) {
            return redirect()->route('home')->with('error', 'nim atau email sudah terdaftar');
        } else {
            Pendaftaran::create([
                'nama' => $request->nama,
                'nim' => $request->nim,
                'prodi' => $request->prodi,
                'email' => $request->email,
                'no_telp' => $request->no_telp,
                'cv' => $imageName,
                'semester' => $request->semester,
                'divisi_1' => $request->divisi_1,
                'divisi_2' => $request->divisi_2,
            ]);
    
            User::create([
                'name' => $request->nama,
                'email' => $request->email,
                'nim' => $request->nim,
                'prodi' => $request->prodi,
            ]);
    
            activity()->withProperties([$request->nama, $request->nim])
                      ->log($request->nama.' melakukan pendaftaran pada divisi '.$request->divisi_1.' dan '.$request->divisi_2);
    
            return redirect()->route('home')->with('success', 'Kamu Berhasil mendaftar');
        }
    }
    

    public function detail($id)
    {
         $id = decrypt($id);
        $detail = Pendaftaran::where('id', $id)->first();
        return view('pendaftaran.detail', compact('detail'));
    }


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







    public function timeLine(){
        $dtTimeLine1 = timeLine::find(1);
        $dtTimeLine2 = timeLine::find(2);
        $cek1 = timeLine::find(1);
        $cek2 = timeLine::find(2);
        $status1 = $cek1->status;
        $status2 = $cek2->status;
        return view('pendaftaran.timeLine', compact('dtTimeLine1','dtTimeLine2','status1','status2'));
    }

    
    public function activasiPendaftaran1(Request $request){
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'status' => 'required',
            'waktu_mulai'  => 'required|before:waktu_berakhir',
            'waktu_berakhir'  => 'required |after:waktu_mulai',
        ], [
            'waktu_mulai.before' => 'Waktu mulai harus sebelum waktu selesai.',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $cek = timeLine::find(1);
        if ($cek) {
            # code...
            $cek->update([
            'nama'=>$request->nama,
            'waktu_mulai'=>$request->waktu_mulai,
            'waktu_berakhir'=>$request->waktu_berakhir,
            'status'=>$request->status,
            ]);
            if ($request->status == 0) {
                # code...
                return redirect(route('view-timeLine'))->with('error', 'pendaftaran di non-aktifkan');
            }
            return redirect(route('view-timeLine'))->with('success', 'pendaftaran aktif');
        }else{
            return redirect('pendaftaran.timeLine')->with('error', 'gagal buka pendaftaran');
        }
        
    }

    public function activasiPendaftaran2(Request $request){
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'status' => 'required',
            'waktu_mulai'  => 'required|before:waktu_berakhir',
            'waktu_berakhir'  => 'required|after:waktu_mulai',
        ], [
            'waktu_mulai.before' => 'Waktu mulai harus sebelum waktu selesai.',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $cek = timeLine::find(2);
        if ($cek) {
            # code...
            $cek->update([
            'nama'=>$request->nama,
            'waktu_mulai'=>$request->waktu_mulai,
            'waktu_berakhir'=>$request->waktu_berakhir,
            'status'=>$request->status,
            ]);
            if ($request->status == 0) {
                # code...
                return redirect(route('view-timeLine'))->with('error', 'pendaftaran di non-aktifkan');
            }
            return redirect(route('view-timeLine'))->with('success', 'pendaftaran aktif');
        }else{
            return redirect('pendaftaran.timeLine')->with('error', 'gagal buka pendaftaran');
        }
    }

    

    // public function formDaftar(){
    //     $cek = timeLine::first();
    //     $pembukaan = Carbon::parse($cek->waktu_mulai)->format('Y-m-d');
    //     $penutupan = Carbon::parse($cek->waktu_berakhir)->format('Y-m-d');
    //     $status = $cek->status;
    //     $currentDate = Carbon::now()->format('Y-m-d');
    
    //     if ($currentDate < $pembukaan) {
    //         // return redirect(route('home'))->with('error', 'pendaftaran dibuka tanggal : '.$cek->waktu_mulai .'-'. $cek->waktu_berakhir);
    //         return redirect()->route('home')->with('error', 'pendaftaran dibuka tanggal : '.$cek->waktu_mulai .' hingga '. $cek->waktu_berakhir);
    //     } elseif ($currentDate > $penutupan) {
    //         //return redirect(route('home'))->with('error', 'pendaftaran ditutup tanggal : '.$cek->waktu_berakhir);
    //         return redirect()->route('home')->with('error', 'pendaftaran ditutup tanggal : '.$cek->waktu_berakhir);
    //     } 
        
    //     elseif ($currentDate >= $pembukaan && $currentDate <= $penutupan && $status == 1) {
    //         return view('pendaftaran.create')->with('success', 'selamat mendaftar');
    //     }
    //     else if($currentDate >= $pembukaan && $currentDate <= $penutupan && $status == 0){
    //         return redirect()->route('home')->with('error', 'pendaftaran di non-aktifkan ');
    //     }
    // }

}
