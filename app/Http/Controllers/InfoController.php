<?php

namespace App\Http\Controllers;

use App\Models\Info;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InfoController extends Controller
{
    public function updateInfo(Request $request)
    {
        // 文字列の前後にある空白、改行等の削除
        $pattern = '/\A[\p{Cc}\p{Cf}\p{Z}]++|[\p{Cc}\p{Cf}\p{Z}]++\z/u';

        $introduction = preg_replace($pattern, '', $request->introduction);
        $link_name = preg_replace($pattern, '', $request->link_name);
        $url = preg_replace($pattern, '', $request->url);

        $info = Info::updateOrCreate(
            ['user_id' => Auth::id()],
            compact(['introduction', 'link_name', 'url'])
        );
        return redirect('/mypage');
    }
}
