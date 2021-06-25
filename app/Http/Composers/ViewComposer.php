<?php

namespace App\Http\Composers;

use App\Models\User;
use App\Models\Article;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class ViewComposer
{
    public function compose(View $view)
    {
        $data = User::find(Auth::user()->id)->getFollowers()->count();
        $view->with('getFollowers', $data);

        $data = User::find(Auth::user()->id)->getFollows()->get();
        $view->with('getFollows', $data);
    }
}
