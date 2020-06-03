<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Carbon\Carbon;
use Auth;
use Image;

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

        $users = User::all();
        $user = auth()->user();
        $user->update($request->all());

        return response()->json(['path' => $this->uploadImage($request->profile_image, $user->id), 'has_image' => $request->hasFile('profile_image')], 200);
    }

    public function checkEmail() {
        $this->validate(request(), [
            'email' => 'required|string|email|max:191|unique:users',
        ]);

        return response()->json(['message' => 'Email available'], 200);
    }

    public function uploadImage($file, $id) {
        if(request()->hasFile('profile_image')) {
            //get filename with extension
            $filenamewithextension = $file->getClientOriginalName();
     
            //get filename without extension
            $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
     
            //get file extension
            $extension = $file->getClientOriginalExtension();
     
            //filename to store
            $filenametostore = $filename.'_'.time().'.'.$extension;
     
            //Upload File
            $file->storeAs('public/profile_images', $filenametostore);
     
            if(!file_exists(public_path('storage/profile_images/crop'))) {
                mkdir(public_path('storage/profile_images/crop'), 0755);
            }
     
            // crop image
            $img = Image::make(public_path('storage/profile_images/'.$filenametostore));
            $croppath = public_path('storage/profile_images/crop/'.$filenametostore);
     
            $img->crop(request()->input('w'), request()->input('h'), request()->input('x1'), request()->input('y1'));
            $img->save($croppath);
     
            // you can save crop image path below in database
            $path = asset('storage/profile_images/crop/'.$filenametostore);
            $user = User::find($id);
            $user->image = $filenametostore;
            $user->save();
     
            return $path;
        }
    }
}
