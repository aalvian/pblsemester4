<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }


    // public function postlogin(Request $request)
    // {
    //     $request->validate([
    //         'email'     => 'required',
    //         'password'  => 'required',
    //     ]);

    //     $data = [
    //         'email'     => $request->email,
    //         'password'  => $request->password,
    //     ];

    //     if(Auth::attempt($data)) {
    //         return redirect()->route('dashboard')->with('succes', 'Kamu Berhasil Login');
    //     } else {
    //         return redirect()->route('login')->with('failed', 'Email atau Password Salah');
    //     }
    // }


    public function postlogin(Request $request)
{
    $validator = Validator::make($request->all(), [
        'email'     => 'required|email',
        'password'  => 'required',
        'g-recaptcha-response' => 'required|recaptcha',
    ]);

    if ($validator->fails()) {
        return redirect()->route('login')
                         ->withErrors($validator)
                         ->withInput()
                         ->with('error', 'Mohon konfirmasi bahwa anda bukan robot.');
    }

    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        $user = Auth::user();
        if ($user->role === 'admin') {
            return redirect()->route('dashboard')->with('succes', 'admin');
        }

        // Cek status di tabel pendaftar
        $pendaftar = Pendaftaran::where('nim', $user->nim)->first();
        //$admin = User::where('role', 'admin')->first();
        if ($pendaftar && $pendaftar->status == 'terima') {
            return redirect()->route('dashboard')->with('succes', 'Kamu Berhasil Login');
        } else if($pendaftar && $pendaftar->status == 'tolak'){
            Auth::logout();
            return redirect()->route('login')->with('error', 'Status pendaftar tidak diterima.');
        }
        else{
            Auth::logout();
            return redirect()->route('login')->with('error', 'Status belum diterima.');
        }
    } else {
        return redirect()->route('login')->with('error', 'Email atau Password Salah');
    }
}

public function verifikasi(Request $request, $token){
    $user = User::where('remember_token', $token)->first();

    $request->validate([
        'password'=>'required|min:8|max:12|',
        'confirm-password' => 'required|min:8|max:12|required_with:password|same:password',
    ]);

    $user->update([
        'password' => bcrypt($request->password),
        'remember_token' => null, // Clear token after password set
    ]);

    $user->assignRole('anggota');


    return redirect('/')->with('success', 'Password has been set successfully.');
}

    public function logout()
    {
        Auth::logout();
        return redirect('/')->with('succes', 'Kamu Berhasil Logout');
    }
}
