<?php

namespace App\Http\Controllers\Teach;

use Illuminate\Http\Request;

use App\Customer;
use App\Models\VClass;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class ClassController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $s = VClass::all();
        $json = ['classes'=>$s];
        return response()->json($json);
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
    public function edit($id)
    {
        $student = Customer::find((int) $id);
        $student->scores = [['avg' => 60, 'id'=>1],
                            ['avg' => 70, 'id'=>2],
                            ['avg' => 70, 'id'=>3],
                            ['avg' => 70, 'id'=>4],
                            ['avg' => 99, 'id'=>5],
                            ['avg' => 78, 'id'=>6],
                            ['avg' => 83, 'id'=>7],
                            ['avg' => 92, 'id'=>8],
                            ['avg' => 99, 'id'=>9],
                            ['avg' => 78, 'id'=>10],
                            ['avg' => 83, 'id'=>11],
                            ['avg' => 92, 'id'=>12],
                            ['avg' => 99, 'id'=>13],
                            ['avg' => 78, 'id'=>14],
                            ['avg' => 83, 'id'=>15],
                            ['avg' => 92, 'id'=>16],
                            ['avg' => 70, 'id'=>17],
                            ['avg' => 70, 'id'=>18],
                            ['avg' => 70, 'id'=>19],
                            ['avg' => 99, 'id'=>20],
                            ['avg' => 78, 'id'=>21],
                            ['avg' => 83, 'id'=>22],
                            ['avg' => 92, 'id'=>23],
                            ['avg' => 99, 'id'=>24],
                            ['avg' => 78, 'id'=>25],
                            ['avg' => 83, 'id'=>26],
                            ['avg' => 92, 'id'=>27],
                            ['avg' => 99, 'id'=>28],
                            ['avg' => 78, 'id'=>29],
                            ['avg' => 83, 'id'=>30],
                            ['avg' => 92, 'id'=>31],
                            ['avg' => 55, 'id'=>32]];
        $json = ['student'=>$student];
        return response()->json($json);
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
