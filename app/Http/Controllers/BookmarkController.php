<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Bookmark;
use Illuminate\Http\Request;
use App\Http\Requests\TestRequest;
use Illuminate\Support\Facades\Auth;

class BookmarkController extends Controller
{
  public function bmAdd(Request $request){
    $bookmark = new Bookmark;
    $bookmark->user_id = Auth::user()->id;
    $bookmark->article_id = $request->id;
    $bookmark->created_at = Carbon::now('Asia/Tokyo');
    $bookmark->updated_at = '0000-00-00 00:00:00';
    $bookmark->save();

    return back();
  }

  public function bmRemove(Request $request){
    $bookmark = Bookmark::where('article_id', $request->id)->where('user_id', Auth::id())->first();
    $bookmark->delete();

    return back();
}
}
