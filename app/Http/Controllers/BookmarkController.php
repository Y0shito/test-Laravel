<?php

namespace App\Http\Controllers;

use App\Models\Bookmark;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookmarkController extends Controller
{
    public function bmAdd(Request $request)
    {
        $bookmark = Bookmark::create(
            [
                'user_id' => Auth::id(),
                'article_id' => $request->id,
            ]
        );
        return back();
    }

    public function bmRemove(Request $request)
    {
        $bookmark = Bookmark::where('article_id', $request->id)
            ->where('user_id', Auth::id())
            ->delete();

        return back();
    }
}
