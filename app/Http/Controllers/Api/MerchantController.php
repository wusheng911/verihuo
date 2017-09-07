<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Services\ApiResponse;
use App\Models\Shop\Merchant;
use Illuminate\Database\Eloquent\Collection;
use Exception;
use DB;
use Log;


class MerchantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $ids = $request->has('ids')?explode(",", $request->input('ids')):[];            
            $merchants = new Collection();

            if (count($ids) > 0) {
                $merchants = Merchant::wherein("id", $ids)->get();
            } else {
                $merchants = Merchant::all();
            }
                        
            $response = ApiResponse::get(false, $merchants->toArray());           
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
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {     
        try {
            $merchant = Merchant::find($id);
                        
            if ($merchant == false) {
                $response = ApiResponse::get(true, ['message' => "Merchant $id Not Found."]);                  
            } else {                       
                $response = ApiResponse::get(false, $merchant->toArray());
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
     * Update the specified resource in storage. Example:
     *
     * @param \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {   
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
                Merchant::wherein("id", $ids)->delete();
            }
                               
            $response = ApiResponse::get(false, $ids);           
        } catch (\Exception $ex) {
            $response = ApiResponse::get(true, ['message' => $ex->getMessage()]);            
        }
        
        return $response;        
    }   
}
