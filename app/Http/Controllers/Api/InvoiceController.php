<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Database\Query\Builder;
use DB;
use App\Services\ApiResponse;
use App\Models\Zone;
use App\Models\Shop\Invoice;
use App\Customer;
use Exception;


class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {       
        try {
            $customer_id = $request->has('user_id')?$request->input('user_id'):null;
            $default_only = $request->has('default_only')?$request->input('default_only'):"0";
            
            if (empty($customer_id)) {
                throw new Exception("用户不存在！");
            }
            
            $query_builder = Invoice::where("customer_id", "=", $customer_id);
            
            if ($default_only == "1") {
                $query_builder = $query_builder->where("is_default", "=", 1);
            }
            
            $invoices = $query_builder->orderBy("is_default", "desc")
                ->orderBy("created_at", "asc")
                ->get();                     
            
            $response = ApiResponse::get(false, $invoices->toArray());           
        } catch (\Exception $ex) {
            $response = ApiResponse::get(true, ['message' => $ex->getMessage()]);
        }
        
        return $response;
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
     * Example: curl -X POST -d 'invoice_title_type=2&user_id=15&content=max&is_default=0' http://localhost/api/invoices
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $invoice_title_type = $request->has("invoice_title_type")?$request->input("invoice_title_type"):null;
            $customer_id = $request->has("user_id")?$request->input("user_id"):null;
            $content = $request->has("content")?$request->input("content"):"";
            $is_default = $request->has("is_default")?$request->input("is_default"):0;
            
            $customer = Customer::find($customer_id);
            
            if (!$customer) {
                throw new Exception("用户不存在！");
            }
            
            if (empty($invoice_title_type)) {
                throw new Exception("发票抬头类型没有指定！");
            }          
            
            $default_invoice = Invoice::where("is_default", "=", 1)
                    ->where("customer_id", "=", $customer->id)
                    ->first();
            
            $invoice = new Invoice;
            $invoice->invoice_titlt_type = $invoice_title_type;
            $invoice->customer_id = $customer->id; 
            $invoice->content = $content;
            $invoice->is_default = $is_default;
            $invoice->save();
            
            if ($default_invoice && ($is_default == "1")) {
                $default_invoice->is_default = 0;
                $default_invoice->save();
            }
            
            $response = ApiResponse::get(false, $invoice->toArray());
          
        } catch (Exception $ex) {
            $response = ApiResponse::get(true, ['message' => $ex->getMessage()]);
        }
        
        return $response;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $invoice = Invoice::where("id", "=", $id)->first();
                        
            if ($invoice == false) {
                $response = ApiResponse::get(true, ['message' => "Invoice $id Not Found."]);                  
            } else {                    
                $response = ApiResponse::get(false, $invoice->toArray());
            }           
        } catch (\Exception $ex) {
            $response = ApiResponse::get(true, ['message' => $ex->getMessage()]);            
        }
        
        return $response;
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
       try {
            $invoice_title_type = $request->has("invoice_title_type")?$request->input("invoice_title_type"):null;
            $customer_id = $request->has("user_id")?$request->input("user_id"):null;
            $content = $request->has("content")?$request->input("content"):null;
            $is_default = $request->has("is_default")?$request->input("is_default"):0;            
                        
            $invoice = Invoice::find($id);
            
            if ($invoice) {                                      
                if ($customer_id != $address->customer_id) {
                    throw new Exception("无法修改其他用户的信息！");
                } 
                
                //如果已经存在默认发票
                $default_invoice = Invoice::where("is_default", "=", 1)
                        ->where("customer_id", "=", $invoice->customer_id)
                        ->first();                
                
                if ($invoice_title_type) {
                    $invoice->invoice_title_type = $invoice_title_type;
                }
                                
                if ($content) {
                    $invoice->content = $content;
                }
                $invoice->is_default = $is_default;
                $invoice->save();
                
                if ($default_invoice && ($is_default == "1") && ($default_invoice->id != $invoice->id)) {
                    $default_invoice->is_default = 0;
                    $default_invoice->save();
                }  
            }
            
            $response = ApiResponse::get(false, $invoice->toArray());
          
        } catch (Exception $ex) {
            $response = ApiResponse::get(true, ['message' => $ex->getMessage()]);
        }
        
        return $response;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ids = (stripos($id, ",") === false)?[$id]:explode(",", $id);
        
        try {
            if (!empty($ids)) {
                Invoice::wherein("id", $ids)->delete();
            }
                               
            $response = ApiResponse::get(false, $ids);           
        } catch (\Exception $ex) {
            $response = ApiResponse::get(true, ['message' => $ex->getMessage()]);            
        }
        
        return $response;
    }
    
}
