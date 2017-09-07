<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use Log;
use Redirect;
use App\User;
use App\Models\Role;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class UserRoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.userrole.list');
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
        $user = User::find($id);
        $checks = [];
        foreach($user->roles as $role){
            $checks[] = $role->id;
        }
        $roles = Role::all()->sortBy("display_name");
        return view('admin.userrole.edit', ['roles' => $roles,
                                        'checks' => $checks,
                                        'user' => $user]);
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
        $user_roles = (array) $request->input('user_roles');
        try{
            $user = User::find($id);
            $user->roles()->sync($user_roles);
            if($backtype==0){
                return Redirect::to(action('Admin\UserRoleController@index'));
            }else{
                return redirect()->action('Admin\UserRoleController@edit', [$user->id]);
            }
        }catch(\Exception $e){
            dd('33', $e);
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
        //
    }

    /**
     * list json by ajax
     *
     */
    public function listJson(Request $request){
        $draw = (int) $request->input('draw');
        $start = (int) $request->input('start');
        $length = (int) $request->input('length');

        Log::info('draw'.$draw.' '.$start.' '.$length);

        $count = User::all()->count();
        $users = User::skip($start)->take($length)->with('roles')->get();
        Log::debug($users);
        $json = ['recordsTotal' => $count,
                 'recordsFiltered' => $count,
                 'draw' => $draw,
                 'data' => $users];

        return response()->json($json);
    }
}
