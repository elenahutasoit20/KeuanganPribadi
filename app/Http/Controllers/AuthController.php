<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin(){ return view('auth.login'); }
    public function showRegister(){ return view('auth.register'); }

    public function register(Request $r){
        $r->validate([
            'name'=>'required|string|max:255',
            'email'=>'required|email|unique:users',
            'password'=>'required|min:6|confirmed'
        ]);
        User::create([
            'name'=>$r->name,
            'email'=>$r->email,
            'password'=>Hash::make($r->password)
        ]);
        return redirect()->route('login')->with('success','Akun berhasil dibuat! Silakan login dengan akun baru Anda.');
    }

    public function login(Request $r){
        $r->validate(['email'=>'required|email','password'=>'required']);
        if(Auth::attempt($r->only('email','password'))){
            return redirect()->route('dashboard');
        }
        return back()->withErrors(['email'=>'Email atau password salah']);
    }

    public function logout(){
        Auth::logout();
        return redirect()->route('login');
    }
}
