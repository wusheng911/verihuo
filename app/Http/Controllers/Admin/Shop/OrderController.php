<?php

namespace App\Http\Controllers\Admin\Shop;

use Illuminate\Http\Request;

use Auth;
use Log;
use Logis;
use JavaScript;
use Carbon\Carbon;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Shop\Order;
use App\Models\Shop\OrderLog;
use App\Models\Shop\OrderStatus;
use App\Models\Shop\Logistic;


class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $status = OrderStatus::all();
        $json = [
            'OrderStatus' => $status,
        ];
        JavaScript::put($json);
        return view("admin.shop.order.index");
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
     *
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
    public function edit(Request $request, $id)
    {
        $status = OrderStatus::all();
        $json = [
            'OrderStatus' => $status,
            'logisticUrl' => action('Admin\Shop\OrderController@listLogisticJson')
        ];
        JavaScript::put($json);
        $order = Order::find((int) $id);
        $logistics = Logistic::All();
        $order->logistic;
        return view("admin.shop.order.edit", ['order' => $order, 'logistics' => $logistics]);
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
        $order = Order::find((int) $id);
        $nLog = new OrderLog();
        $old = $order->replicate();
        if($request->input('step') == 'paid'){
            $order->paid_status = 1;
            $order->paid_at = Carbon::now();
            $nLog->remark = '后台设置为已付款';
            $order->update();
        }
        if($request->input('step') == 'mod'){
            $b = $this->validate(
                $request, ['order.price' => 'required|numeric',]);
             $price = $request->input('order.price');
             $nLog->remark = '后台改价: '. $old->total. ' -> '. $price;
             $order->total = $price;
             $order->update();
        }
        switch($request->input('step')){
          case OrderStatus::Confirmed:
          case OrderStatus::Preparing:
          case OrderStatus::Shipping:
              $order->status = OrderStatus::next($order->status);
              $nLog->remark = $old->orderStatus->description.' -> '.$order->orderStatus->description;
              $order->update();
              break;
          default :
              break;
        }

        if($request->input('step') == OrderStatus::Shipping){
            $order->logistic_id = (int) $request->input('order.logistic_id');
            $order->logistic_no = $request->input('order.logistic_no');
            $order->update();
        }

        // 1. save old
        $log = OrderLog::where('order_id', '=', $id)->orderBy('id', 'desc')->first();
        if(!empty($log)){
            $version = $log->nextVersionId();
        }else{
            $version = 0;
        }
        $nLog->version = $version;
        $nLog->mender = Auth::guard('admin')->user()->id;
        $nLog->order_id = $id;
        $nLog->info = serialize($old);
        $nLog->save();
        return redirect()->action('Admin\Shop\OrderController@edit', $id);;
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

    public function listLogisticJson(Request $request){
        $id = (int) $request->input('id');
        $json = ['status' => 'failed'];
        if(!empty($id)){
            $order = Order::find($id);
            $ljson = Logis::get($order->logistic->code, $order->logistic_no)->traces;
            $json['status'] = 'success';
            $json['data'] = json_encode($ljson);
        }

        return response()->json($json);
    }
                                     
    /**
     * list json by ajax
     *
     */
    public function listJson(Request $request){
        $draw = (int) $request->input('draw');
        $start = (int) $request->input('start');
        $length = (int) $request->input('length');
        $search =  $request->input('search');
        $sn = $request->input('sn');
        $consigneeAddress = $request->input('consignee_address');
        $columns = $request->input('columns');
        $orderBy = $request->input('order');
        $whereIn1 = [];
        $clause = new Order();
        if(!empty($sn)){
            $sns = explode('-', $sn);
            if(count($sns) == 2){
                if($sns[0] > $sns[1]){
                    $ssn = $sns[1];
                    $esn = $sns[0];
                }else{
                    $ssn = $sns[0];
                    $esn = $sns[1];
                }
                $clause = $clause->where('sn', '>=', $ssn);
                $clause = $clause->where('sn', '<=', $esn);
            }else{
                $clause = $clause->where('sn', 'like', '%'.$sn.'%');
            }
        }
        if(!empty($request->input('created_at'))){
            $cat = $request->input('created_at');
            $clause = $clause->where('created_at', '>=', $cat['start'])
                    ->where('created_at', '<=', $cat['end']); 
        }
        if(!empty($request->input('delivered_at'))){
            $cat = $request->input('delivered_at');
            $clause = $clause->where('delivered_at', '>=', $cat['start'])
                    ->where('delivered_at', '<=', $cat['end']); 
        }
        if(!empty($consigneeAddress)){
            $clause = $clause->where('consignee_address', 'like', "%$consigneeAddress%");
        }
        if(!empty($request->input('status'))){
            $clause = $clause->whereIn('status', $request->input('status'));
        }
        if(!empty($orderBy[0])){
            $pos = $orderBy[0]['column'];
            $c = $columns[$pos]['data'];
            $dir = $orderBy[0]['dir'];
            $clause = $clause->orderBy($c, $dir);
        }
        $recordsFiltered = $clause->count();
        $count = Order::count();
        $orders = $clause->skip($start)->take($length)->get();

        foreach($orders as $order){
            $order->orderStatus;
            $order->consigneeZone;
        }
        
        $json = ['recordsTotal' => $count,
                 'recordsFiltered' => $recordsFiltered,
                 'draw' => $draw,
                 'data' => $orders];
        return response()->json($json);
    }
    
}
