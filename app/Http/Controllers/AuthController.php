<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class AuthController extends Controller {
    public function index() {
        if (Auth::check()) return redirect('/dashboard');
        return view('auth.login');
    }
    public function login(Request $request) {
        $email = $request->email;
        $password = $request->password;
        $user = DB::select("SELECT * FROM users WHERE email = '$email' AND active = 1");
        if (empty($user)) return back()->with('error','Invalid email or password');
        $user = $user[0];
        if ($user->password !== md5($password)) return back()->with('error','Invalid email or password');
        Auth::loginUsingId($user->id);
        return redirect('/dashboard');
    }
    public function logout() { Auth::logout(); return redirect('/login'); }
}
