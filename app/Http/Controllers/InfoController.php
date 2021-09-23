<?php

namespace App\Http\Controllers;

use App\{Models\Info, Traits\Spaceremoval};
use Illuminate\{Http\Request, Support\Facades\Auth};

class InfoController extends Controller
{
    use Spaceremoval;

    public function updateInfo(Request $request)
    {
        $introduction = InfoController::spaceRemoval($request->introduction);
        $link_name = InfoController::spaceRemoval($request->link_name);
        $url = InfoController::spaceRemoval($request->url);

        $info = Info::updateOrCreate(
            ['user_id' => Auth::id()],
            compact(['introduction', 'link_name', 'url'])
        );
        return redirect('/mypage');
    }
}
