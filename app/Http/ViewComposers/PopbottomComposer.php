<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;

use App\Models\AdPosition;

class PopbottomComposer
{
    /**
     * Create a new profile composer.
     *
     * @param  UserRepository  $users
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $a = AdPosition::getAdPositions('PC|Popbottom|A');
        if(!empty($a[0]->nodes[0]->localAttributes['Image Path']->values[1]['value'])){
            $url = $a[0]->nodes[0]->localAttributes['Image Path']->values[1]['value'];
            $view->with('popbottom', $url);
        }
    }
}