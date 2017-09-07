<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use Log;
use File;
use JavaScript;
use App\Models\Visitor;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Jobs\GenerateContentTags;

class VisitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.visit.list');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $visitor = new Visitor();
        return view('admin.visit.edit', ['visitor' => $visitor]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    	$backtype =(int) $request['other']['backtype'];

        $visitor = Visitor::create($inputs);

        if($backtype==0){
        	return redirect()->action('Admin\VisitController@index');
        }else{
        	return redirect()->action('Admin\VisitController@edit', [$visitor->id]);
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $visitor = Visitor::find($id);
        return view('admin.visit.show', ['visitor' => $visitor]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $json = [
            'visitorid' => $id,
        ];
        JavaScript::put($json);
        $visitor = Visitor::find($id);
        return view('admin.visitor.edit', ['visitor' => $visitor]);
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
        
        $backtype =(int) $request['other']['backtype'];
        $inputs = $request->input('visitor');
        $visitor = Visitor::find((int) $id);
        if($backtype==0){
        	return redirect()->action('Admin\VisitorController@index');
        }else{
            return redirect()->action('Admin\VisitorController@edit', $id);;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $json = ['id'=>$id + 1];
        Log::info(response()->json($json));
        $visitor = Visitor::find($id);
        $visitor->delete();
        return response()->json($json);
        //
    }

    /**
     * list json by ajax
     *
     */
    public function listJson(Request $request){
        $aimType = 1;
        $draw = (int) $request->input('draw');
        $start = (int) $request->input('start');
        $length = (int) $request->input('length');
        $search =  $request->input('search');
        if(!empty($search['value'])){
            $skey = $search['value'];
            $visitors = Visitor::where('type',$aimType)->where('name', 'LIKE', "%$skey%")->skip($start)->take($length)->orderBy('id', 'desc')->get();
        }else{
            $visitors = Visitor::where('type',$aimType)->skip($start)->take($length)->orderBy('id','desc')->get();
        }

        $count = Visitor::all()->count();
        
        $json = ['recordsTotal' => $count,
                 'recordsFiltered' => $count,
                 'draw' => $draw,
                 'data' => $visitors];

        return response()->json($json);
    }
}
