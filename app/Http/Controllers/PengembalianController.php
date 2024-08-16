<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PengembalianController extends Controller
{
    public function index()
    {
        $dtpengembalian = Pengembalian::all();
        return view('alat.pengembalian.index', compact('dtpengembalian'));
    }
    public function create($id)
    {
        $peminjaman = Peminjaman::findOrFail($id); // Mengambil peminjaman berdasarkan ID
        $alat = Alat::where('nama_barang', $peminjaman->nama_barang)->firstOrFail(); // Mengambil detail alat berdasarkan nama barang
        return view('alat.pengembalian.kembali', compact('peminjaman', 'alat'));
    }
    public function store(Request $request)
    {
        // Validasi permintaan yang masuk
        $validatedData = $request->validate([
            'nama_barang' => 'required',
            'jml_barang' => 'required|numeric|min:1',
            'tggl_kembali' => 'required|date',
            'tggl_pinjam' => 'required|date',
            'status' => 'required',
            'image' => 'nullable|file|mimes:jpeg,png,jpg|max:10240',
        ]);
    
        // Simpan gambar ke storage jika ada
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('image', $imageName, 'public');
        } else {
            $imageName = null; // Tidak ada gambar yang diunggah
        }
    
        // Cari data peminjaman yang sesuai
        $peminjaman = Peminjaman::where('nama_barang', $validatedData['nama_barang'])
            ->where('tggl_pinjam', $validatedData['tggl_pinjam'])
            ->first();
    
        if (!$peminjaman) {
            return redirect()->back()->with('error', 'Data peminjaman tidak ditemukan.');
        }
    
        // Simpan data ke tabel pengembalian
        $user = Auth::id(); // Ambil ID pengguna yang sedang login
    
        $pengembalian = Pengembalian::create([
            'id_peminjaman' => $peminjaman->id,
            'nama' => $peminjaman->nama,
            'nim' => $peminjaman->nim,
            'prodi' => $peminjaman->prodi,
            'nama_barang' => $validatedData['nama_barang'],
            'jml_barang' => $validatedData['jml_barang'],
            'tggl_kembali' => $validatedData['tggl_kembali'],
            'tggl_pinjam' => $validatedData['tggl_pinjam'],
            'status' => $validatedData['status'],
            'image' => $imageName,
            'petugas_id' => $user,
        ]);
    
        // Handle kesalahan atau sukses dalam penyimpanan
        if ($pengembalian) {
            // Hapus data peminjaman setelah berhasil dikirimkan ke tabel pengembalian
            $peminjaman->delete();
    
            return redirect()->route('pengembalian')->with('success', 'Data berhasil dikembalikan.');
        } else {
            return redirect()->back()->with('error', 'Gagal menyimpan data pengembalian.');
        }
    }
    


    public function destroy(string $id)
    {
        $alat = Pengembalian::findOrFail($id);

        if ($alat->image) {
            Storage::delete('public/gambar/' . $alat->image);
        }
        $alat->delete();
        return back()->with('toast_success', 'Data Berhasil Dihapus');
    }
}   