<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use App\Models\Divisi;
use App\Models\Jadwal;
use App\Models\Presensi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PresensiController extends Controller
{

    public function view()
    {
        $dtPresensi = Presensi::all();

        return view('presensi.view', compact('dtPresensi'));
    }

    public function index()
    {
        $currentDate = Carbon::now()->format('Y:m:d');

        $user = Auth::user();
        $nim = $user->nim;
        $pendaftar = Pendaftaran::where('nim', $nim)->first();
        $divisiPendaftar1 = $pendaftar->divisi_1;
        $divisiPendaftar2 = $pendaftar->divisi_2;
        $cek1 = Presensi::where([
            'nama' => $user->name,
            'nim' => $user->nim,
            'tanggal' => $currentDate,
            'divisi' => $divisiPendaftar1,
        ])->first();

        $cek2 = Presensi::where([
            'nama' => $user->name,
            'nim' => $user->nim,
            'tanggal' => $currentDate,
            'divisi' => $divisiPendaftar2,
        ])->first();

        $statusPresensi1 = '';
        $statusPresensi2 = '';
        if ($cek1) {
            # code...
            $statusPresensi1 = 'sudah presensi';
        }if ($cek2) {
            $statusPresensi2 = 'sudah presensi';
        }
        
        $dtDivisi = Divisi::all();
        $user = Auth::user();
        $pendaftar = Pendaftaran::where('nim', $user->nim)->first();

        $jadwalDivisi1 = collect(); // Inisialisasi sebagai koleksi kosong
        $jadwalDivisi2 = collect(); // Inisialisasi sebagai koleksi kosong

        if ($pendaftar) {
            if ($pendaftar->divisi_1) {
                $divisi1 = Divisi::where('nama', $pendaftar->divisi_1)->first();
                if ($divisi1) {
                    $jadwalDivisi1 = Jadwal::where('divisi_id', $divisi1->id)->get();
                }
            }

            if ($pendaftar->divisi_2) {
                $divisi2 = Divisi::where('nama', $pendaftar->divisi_2)->first();
                if ($divisi2) {
                    $jadwalDivisi2 = Jadwal::where('divisi_id', $divisi2->id)->get();
                }
            }

            return view('presensi.index', compact('pendaftar', 'jadwalDivisi1', 'jadwalDivisi2', 'dtDivisi', 'statusPresensi1', 'statusPresensi2'));
        }
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'tanggal' => 'nullable',
            'divisi' => 'required',
            'bukti' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
        ], [
            'bukti.required' => 'Upload foto dulu',
        ]);

        $currentDate = Carbon::now()->format('Y-m-d');
        $currentTime = Carbon::now()->format('H:i:00'); // Ambil waktu sekarang

        $takeDivisi = Divisi::where('nama', $request->divisi)->first();
        $takeTenggat = $takeDivisi->tenggat;
        //dd($currentTime);
        $fotoFile = $request->file('bukti');
        $namaFileUnik = Str::uuid() . '' . time() . '' . $fotoFile->getClientOriginalName();
        $fotoPath = $fotoFile->storeAs('public/buktiPresensi', $namaFileUnik);
        
        $user = Auth()->user();
        $cek = Presensi::where([
            'nama' => $user->name,
            'nim' => $user->nim,
            'tanggal' => $currentDate,
            'divisi' => $request->divisi,
        ])->first();

        // Buat if untuk validasi tenggat
        if ($cek) {
            return redirect()->route('view-presensi')->with('error', 'Anda sudah presensi');
        } else {
            if ($currentTime > $takeTenggat) {
                return redirect()->route('view-presensi')->with('error', 'Presensi ditutup');
            } else {
                Presensi::create([
                    'nama' => $user->name,
                    'nim' => $user->nim,
                    'divisi' => $request->divisi,
                    'tanggal' => $currentDate,
                    'bukti' => $namaFileUnik,
                ]);
                return redirect()->route('view-presensi')->with('toast_success', 'Berhasil presensi');
            }
        }
    }


    public function activatePresensiView()
    {
        $dtDivisi = Divisi::all();
        return view('presensi.aktifasi', compact('dtDivisi'));
    }

    public function toggleStatus(Request $request)
{
    // Ambil data divisi berdasarkan ID
    $divisi = Divisi::findOrFail($request->id);
    
    // Toggle status aktifasi
    $newStatus = $divisi->aktifasi ? 0 : 1;
    $divisi->aktifasi = $newStatus;
    $divisi->tenggat = $request->tenggat; // Update tenggat
    $currentTime = Carbon::now()->format('H:i:00');
    
    $divisi->save(); // Simpan perubahan

    // Kirim respon JSON bahwa perubahan berhasil
    return response()->json(['success' => true, 'message' => 'Status berhasil diubah']);
}

    
    public function getStatus(Request $request)
    {
        // Ambil data divisi berdasarkan ID
        $status = Divisi::findOrFail($request->id);

        // Kirim respon JSON berisi status divisi
        return response()->json(['status' => $status->aktifasi ? 'active' : 'inactive']);
    }
}
