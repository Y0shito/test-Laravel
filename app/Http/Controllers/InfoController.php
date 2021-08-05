<?php

namespace App\Http\Controllers;

use App\Models\Info;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InfoController extends Controller
{
    public function updateInfo(Request $request)
    {
        $info = Info::updateOrCreate(
            ['user_id' => Auth::id()],
            ['introduction' => $request->introduction, 'link_name' => $request->link_name, 'url' => $request->url]
        );
        return redirect('/mypage');
    }
}
