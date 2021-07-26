<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
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
        return view('layouts.userpage', compact('articles', 'user'));
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

    //ここからTwitter認証
    public function getAuth()
    {
        return Socialite::driver('twitter')->redirect();
    }

    // public function callback()
    // {
    //     $getUser = Socialite::driver('twitter')->user();

    //     // 新しいユーザーを作成
    //     $user = new User();
    //     $user->name = $getUser->name;
    //     $user->remember_token = $getUser->token;
    //     $user->created_at = now('Asia/Tokyo');
    //     $user->updated_at = now('Asia/Tokyo');
    //     $user->save();

    //     dd($getUser);
    // }

    public function callback()
    {
        //新規登録からのログインは問題なし、ただしログインが出来ない
        //ログイン通すにはガードを弄る

        $getUser = Socialite::driver('Twitter')->user();

        // 既に存在するユーザーかを確認
        $check = User::where('name', $getUser->name)->exists();

        if ($check) {
            // 既存のユーザーはログインしてトップページへ
            Auth::login($getUser);
            dd($getUser);
            return redirect('/index');
        } else {
            // 新しいユーザーを作成
            $user = new User();
            $user->name = $getUser->name;
            $user->save();

            Auth::login($user, true);
            return redirect('/index');
        }
    }
}
