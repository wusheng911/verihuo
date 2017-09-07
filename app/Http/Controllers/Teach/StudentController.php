<?php

namespace App\Http\Controllers\Teach;

use Illuminate\Http\Request;

use DB;
use Log;
use App\Customer;
use App\Models\Ccscore;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $s = Customer::all();
        $json = ['students'=>$s];
        return response()->json($json);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
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
        $r = $request->input('score');
        unset($r['id']);
        $s = new Ccscore;
        $s->customer_id = $r['customer_id'];
        $s->class_id = $r['class_id'];
        $s->at = $r['at'];
        $s->c1 = $r['c1'];
        $s->c2 = $r['c2'];
        $s->c3 = $r['c3'];
        $s->c4 = $r['c4'];
        $s->c5 = $r['c5'];
        $s->a1 = $r['a1'];
        $s->a2 = $r['a2'];
        $s->a3 = $r['a3'];
        $s->a4 = $r['a4'];
        $s->a5 = $r['a5'];
        $s->save();
        return response()->json($s);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $c = Customer::find($id);
        unset($c['password']);

        $c->fschools = DB::table('customer_fschool as cf')
                     ->join('schools as s', 's.id', '=', 'cf.school_id')
                     ->where('cf.customer_id', '=', $id)
                     ->orderBy('cf.pos', 'asc')
                     ->get();

        return response()->json($c);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $student = Customer::find((int) $id);
        $scores = Ccscore::where(['customer_id' => (int) $id])->get();
        $student->scores = $scores;
        $json = ['student'=>$student];
        return response()->json($json);
    }

    public function fgrade($id){
        $c = Customer::find($id);
        $fgrade = DB::table('final_reports')
                ->where('customer_id', '=', $c->id)->first();
        if(!$fgrade){
            DB::table('final_reports')
                ->insert(['customer_id' => $id]);
            $fgrade = DB::table('final_reports')
                    ->where('customer_id', '=', $c->id)->first();
        }
        return response()->json($fgrade);
    }

    public function update_fgrade(Request $request, $id){

        $fgrade = $request->input('fgrade');

        $data['final'] = $fgrade['final'];
        $data['fschool4'] = $fgrade['fschool4'];
        $data['professor'] = $fgrade['professor'];
        $data['fschool5'] = $fgrade['fschool5'];
        $data['test_score'] = $fgrade['test_score'];
        $data['recommend_school1'] = $fgrade['recommend_school1'];
        $data['research_exp'] = $fgrade['research_exp'];
        $data['fschool2'] = $fgrade['fschool2'];
        $data['tip1'] = $fgrade['tip1'];
        $data['work_exp'] = $fgrade['work_exp'];
        $data['award'] = $fgrade['award'];
        $data['tip2'] = $fgrade['tip2'];
        $data['recommend_school2'] = $fgrade['recommend_school2'];
        $data['tip3'] = $fgrade['tip3'];
        $data['fschool1'] = $fgrade['fschool1'];
        $data['gpa_grade'] = $fgrade['gpa_grade'];
        $data['fschool3_percent'] = $fgrade['fschool3_percent'];
        $data['updated_at'] = $fgrade['updated_at'];
        $data['activity'] = $fgrade['activity'];
        $data['fschool4_percent'] = $fgrade['fschool4_percent'];
        $data['fschool3'] = $fgrade['fschool3'];
        $data['course'] = $fgrade['course'];
        $data['fschool2_percent'] = $fgrade['fschool2_percent'];
        $data['action_plan'] = $fgrade['action_plan'];
        $data['recommend_school3'] = $fgrade['recommend_school3'];
        $data['fschool1_percent'] = $fgrade['fschool1_percent'];
        $data['created_at'] = $fgrade['created_at'];
        $data['fschool5_percent'] = $fgrade['fschool5_percent'];
        $data['customer_id'] = $fgrade['customer_id'];

        DB::table('final_reports')
            ->where(['customer_id' => $id])
            ->update($data);
        return response()->json($data);
    }

    public function advise($id)
    {
        $r['id'] = 0;
        $ad = DB::table('customer_advises')
            ->where(['customer_id' => (int)$id])->first();
        if($ad){
            $r = $ad;
        }
        return response()->json($r);
    }

    public function update_advise(Request $request, $id)
    {
        $advises = $request->input('advises');
        $a1 = isset($advises['advise1']) ? $advises['advise1'] : '' ;
        $a2 = isset($advises['advise2']) ? $advises['advise2'] : '' ;
        $ad = DB::table('customer_advises')
            ->where(['customer_id' => (int)$id])->get();
        if($ad){
            DB::table('customer_advises')
                ->where(['customer_id' => $id])
                ->update(['advise1' => $a1,
                          'advise2' => $a2]);
        }else{
            DB::table('customer_advises')
                ->insert(['customer_id' => $id,
                          'advise1' => $a1,
                          'advise2' => $a2]);
        }
        return response()->json($advises);
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
        $r = $request->input('score');
        $s = Ccscore::find((int) $id);
        $s->customer_id = $r['customer_id'];
        $s->class_id = $r['class_id'];
        $s->at = $r['at'];
        $s->c1 = $r['c1'];
        $s->c2 = $r['c2'];
        $s->c3 = $r['c3'];
        $s->c4 = $r['c4'];
        $s->c5 = $r['c5'];
        $s->a1 = $r['a1'];
        $s->a2 = $r['a2'];
        $s->a3 = $r['a3'];
        $s->a4 = $r['a4'];
        $s->a5 = $r['a5'];
        $s->save();
        return response()->json($s);
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
