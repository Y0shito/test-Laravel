<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Bookmark;
use Illuminate\Http\Request;
use App\Http\Requests\TestRequest;
use Illuminate\Support\Facades\Auth;

class BookmarkController extends Controller
{
//   public function myBookmarks(Request $request){
//     $myBookmarks = Bookmark::where('user_id', Auth::user()->id)->first();
//     return view('layouts.mypage', ['myBookmarks' => $myBookmarks]);
// }
}
