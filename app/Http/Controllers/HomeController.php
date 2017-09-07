<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Models\AdPosition;
use PhpParser\Node\Stmt\Label;
use App\Http\Controllers\Controller;
use App\Services\ApiClient;
use App\Models\Node;
use App\Models\ContentCategory;
use Log;
use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $homeKvs = [];
		$homeKVText =[];
		$homeItems = [];
		$homeItemHides = [];
		$hides = AdPosition::getAdPositions('PC|Item|Hide');
        $kvs = AdPosition::getAdPositions("PC|Home|KV");
        $kvText = AdPosition::getAdPositions("PC|Home|Text");

        $items = AdPosition::getAdPositions("PC|Home|Item");
        $homeKvs = json_decode(json_encode($kvs), true);
        $homeItems = json_decode(json_encode($items), true);
		$homeItemHides = json_decode(json_encode($hides),true);
		$homeKVText = json_decode(json_encode($kvText),true);

        $nodes = Node::adPositionsToNodes($homeKvs);
        return view('pc.home',compact('nodes','homeKvs','homeItems','homeItemHides','homeKVText'));

    }
}
