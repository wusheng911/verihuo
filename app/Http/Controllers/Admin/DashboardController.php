<?php

namespace App\Http\Controllers\Admin;

use App\Models\NodeAttribute;

use JavaScript;
use App\Customer;
use App\Models\NodeType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Shop\Order;
use App\Models\Content;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\AdPosition;

class DashboardController extends Controller
{
    protected $guard = 'admin';

    /**
     * 后台首页控制面板数据统计
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [];
        $today = Carbon::today()->toDateString();
        $prevDay = Carbon::today()->subSeconds(1)->toDateTimeString();
        $nextDay = Carbon::today()->addSeconds(86400-1)->toDateTimeString();
        $yesterday = Carbon::yesterday()->toDateString();
        $lastWeekStart = Carbon::today()->subDays(7)->toDateString();
        $lastMonth = Carbon::today()->subDays(30)->toDateString();

        //今天，昨天，最近一周用户统计
        $data['todayCustomersTotal'] = Customer::whereBetween('created_at',[$today,$nextDay])->get()->count();
        $data['yesterdayCustomersTotal'] = Customer::whereBetween('created_at',[$yesterday,$prevDay])->get()->count();
        $data['lastWeekCustomersTotal'] = Customer::whereBetween('created_at',[$lastWeekStart,$prevDay])->get()->count();

        //今天，昨天，最近一周文章阅读量统计
        $data['todayArticleViewTotal'] = Content::whereBetween('created_at',[$today,$nextDay])->sum('view_count');
        $data['yesterdayArticleViewTotal'] = Content::whereBetween('created_at',[$yesterday,$prevDay])->sum('view_count');
        $data['lastWeekArticleViewTotal'] = Content::whereBetween('created_at',[$lastWeekStart,$prevDay])->sum('view_count');

        //今天，昨天，最近一周订单统计
        $data['todayOrdersTotal'] = Order::whereBetween('created_at',[$today,$nextDay])->get()->count();
        $data['yesterdayOrdersTotal'] = Order::whereBetween('created_at',[$yesterday,$prevDay])->get()->count();
        $data['lastWeekOrdersTotal'] = Order::whereBetween('created_at',[$lastWeekStart,$prevDay])->get()->count();

        //最近30天订单状况
        $lastMonthOrders = Order::whereBetween('created_at',[$lastMonth,$yesterday])->get();
        foreach ($lastMonthOrders as $key=>$lastMonthOrder) {
            $created_at = $lastMonthOrder->created_at;
            $lastMonthOrders[$key]->created_time = Carbon::parse($created_at)->toDateString();
        }
        $lastMonthOrdersTotal = [];
        for($i=30;$i>=0;$i--) {
            $t = Carbon::today()->subDays($i)->toDateString();
            $lastMonthOrdersTotal[] = $lastMonthOrders->where('created_time',$t)->count();
        }

        //最近30天文章浏览状况
        $lastMonthArticles = Content::whereBetween('created_at',[$lastMonth,$yesterday])->orderBy('view_count', 'desc')->get();
        $allArticlesViews = $lastMonthArticles->sum('view_count');
        //出现了 除数为0 的情况，临时修改 sw
        if($allArticlesViews == 0){
            $allArticlesViews = 1;
        }
        $topArticles = $lastMonthArticles->take(5);
        $lastMonthArticlesViewTotal = [];
        $top5ViewTotal = 0;
        foreach ($topArticles as $key=>$topArticle) {
            $view_count = number_format(($topArticle->view_count)/$allArticlesViews, 3)*100;
            $lastMonthArticlesViewTotal[$key]['name'] = $topArticle->title;
            $lastMonthArticlesViewTotal[$key]['y'] = $view_count;
            $lastMonthArticlesViewTotal[$key]['url'] = url('view/article/'.$topArticle->id);
            $top5ViewTotal = $top5ViewTotal + $view_count;
        }
        //$lastMonthArticlesViewTotal[5][0] = '其他';
        //$lastMonthArticlesViewTotal[5][1] = 100-$top5;
        //突出最近30天内top除外其他文章阅读总数的比例
        //$lastMonthArticlesViewTotal[5]['name'] = '其他';
        //$lastMonthArticlesViewTotal[5]['url'] = '#';
        //$lastMonthArticlesViewTotal[5]['y'] = 100-$top5ViewTotal;
        //$lastMonthArticlesViewTotal[5]['sliced'] = true;
        //$lastMonthArticlesViewTotal[5]['selected'] = true;

        //dd($lastMonthArticlesViewTotal);

        JavaScript::put([
            'lastMonthOrdersTotalData' => $lastMonthOrdersTotal,
            'lastMonthArticlesViewTotalData' => $lastMonthArticlesViewTotal
        ]);
        return view('admin.dashboard', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *UND
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
