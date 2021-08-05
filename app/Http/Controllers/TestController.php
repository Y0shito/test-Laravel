<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use App\Models\Info;
use App\Models\Article;
use Illuminate\Http\Request;
use App\Http\Requests\TestRequest;
use Illuminate\Support\Facades\Auth;

class TestController extends Controller
{
    public function index()
    {
        return view('layouts.index');
    }

    public function auth()
    {
        return view('layouts.auth');
    }

    public function postAuth(Request $request)
    {
        $email = $request->email;
        $password = $request->password;

        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            return redirect('/index');
        } else {
            $error = 'アドレスまたはパスワードが違います';
            return view('layouts.auth', ['error' => $error]);
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/index');
    }

    public function mypage()
    {
        $user = Auth::user();
        return view('layouts.mypage', ['user' => $user]);
    }

    public function userpage($userId)
    {
        $articles = Article::where('open', 1)->where('author_id', $userId)->get();
        $user = User::find($userId);
        $info = Info::where('user_id', $userId)->first();
        return view('layouts.userpage', compact('articles', 'user', 'info'));
    }

    public function follow(Request $request)
    {
        User::find(Auth::id())->getFollows()->attach($request->id);
        return back();
    }

    public function unFollow(Request $request)
    {
        User::find(Auth::id())->getFollows()->detach($request->id);
        return back();
    }

    public function create()
    {
        return view('layouts.create');
    }

    public function preview()
    {
        return view('layouts.preview');
    }

    public function article()
    {
        return view('layouts.article');
    }

    public function result()
    {
        return view('layouts.result');
    }

    public function config()
    {
        $checkInfo = User::find(Auth::id());
        if (!empty($checkInfo)) {
            $info = $checkInfo->getInfo;
            return view('layouts.config', ['info' => $info]);
        }
        return view('layouts.config');
    }

    //ここからTwitter認証
    public function getAuth()
    {
        return Socialite::driver('twitter')->redirect();
    }

    public function callback()
    {
        $user = Socialite::driver('Twitter')->user();
        Auth::login(
            User::firstOrCreate([
                'name' => $user->getName(),
            ]),
            true
        );
        return redirect('/index');
    }
}
