<?php

namespace App\Http\Composers;

use Illuminate\View\View;

class ViewComposer
{
    public function compose(View $view)
    {
        $view->with('message', 'test');
    }
}
