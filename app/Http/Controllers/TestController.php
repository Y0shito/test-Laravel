<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\TestRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class TestController extends Controller
{
    public function index(){
        return view('layouts.index');
    }

    public function auth(){
        return view('layouts.auth');
    }

    public function postAuth(Request $request){
        $email = $request->email;
        $password = $request->password;

        if(Auth::attempt(['email' => $email, 'password' => $password])){
            return redirect('/index');
        }else{
            $error = 'アドレスまたはパスワードが違います';
            return view('layouts.auth', ['error' => $error]);
        }
    }

    public function logout(){
        Auth::logout();
        return redirect('/index');
    }

    public function mypage(){
        $user = Auth::user();
        return view('layouts.mypage', ['user' => $user]);
    }

    public function create(){
        return view('layouts.create');
    }

    public function article(){
        return view('layouts.article');
    }

    public function result(){
        return view('layouts.result');
    }

}
