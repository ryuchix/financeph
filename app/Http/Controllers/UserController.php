<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Carbon\Carbon;
use Auth;

class UserController extends Controller
{
    public function home() {
    	return view('home');
    }

    public function profile() {
        $user = auth()->user();
        $users = User::all();
        return view('profile', compact('user', 'users'));
    }

    // display login form
    public function showloginForm() {
    	return view('profile');
    }


    // user login
    public function login(Request $request) {
        $credentials = request(['email', 'password']);
        if (!auth()->attempt($credentials)) {
            return response()->json(['message' => 'Invalid email or password'], 400); 
        }
        $user = auth()->user();
        $user->online = 1;
        $user->last_login = Carbon::now()->toDateTimeString();
        $user->save();
        $users = User::all();

        return view('profile', compact('user', 'users'));

    }

    // user login
    public function logout(Request $request) {
        
        $user = Auth::user();
        $user->online = 0;
        $user->save();

        Auth::logout();

        return view('home');
    }

    // display login form
    public function showRegistrationForm() {
    	return view('register');
    }

    public function register(Request $request) {
        $this->validate(request(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:191|unique:users',
            'password' => 'required|string|min:3|confirmed',
        ]);

    	$user = User::create($request->all());
        return redirect()->back();
    }

    public function updateProfile(Request $request) {
        $this->validate(request(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:191|unique:users,id',
        ]);

        $user = auth()->user();
        $user->update($request->all());

        return view('profile', compact('user'));
    }

    public function checkEmail() {
        $this->validate(request(), [
            'email' => 'required|string|email|max:191|unique:users',
        ]);

        return response()->json(['message' => 'Email available'], 200);  
    }
}
