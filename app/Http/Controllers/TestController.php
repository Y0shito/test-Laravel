<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use App\Models\Article;
use App\Models\Info;
use App\Models\User;
use Illuminate\Http\Request;
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

    public function userDelete()
    {
        User::destroy(Auth::id());
        return redirect('/index');
    }

    public function mypage()
    {
        $user = Auth::user();

        $checkInfo = User::find(Auth::id());
        if (!empty($checkInfo)) {
            $info = $checkInfo->getInfo;
        }

        return view('layouts.mypage', compact('user', 'info'));
    }

    public function userpage($userId)
    {
        $articles = Article::openArticles()->where('author_id', $userId)->get();
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
