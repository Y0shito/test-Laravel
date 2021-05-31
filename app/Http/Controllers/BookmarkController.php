<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Bookmark;
use Illuminate\Http\Request;
use App\Http\Requests\TestRequest;
use Illuminate\Support\Facades\Auth;

class BookmarkController extends Controller
{
    // public function myBookmarks(){
    //     $bookmarks = Bookmark::where('user_id', Auth::user()->id)->get();
    //     return view('layouts.mypage', ['bookmarks' => $bookmarks]);
    // }
    
    // public function db(Request $request){
    //     $bookmarks = DB::select('select * from bookmarks');
    //     return view('layouts.mypage',['bookmarks' => $bookmarks]);
    // }

}
