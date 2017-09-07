<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use DB;
use Log;
use Auth;
use Config;
use Redirect;
use Validator;
use App\Models\Role;
use App\Models\Permission;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.role.list');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $role = new Role();
        $perms = Permission::all()->sortBy("name");
        return view('admin.role.edit', ['role' => $role,
                                        'checks' => [],
                                        'perms' => $perms]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all()['role'], [
                'name' => 'required|unique:roles|max:255',
                'display_name' => 'required'
            ], trans('validation.entrust'));

        if ($validator->fails()) {
            return redirect(action('Admin\RoleController@create'))
                ->withErrors($validator)
                ->withInput();
        }

        $backtype =(int) $request['other']['backtype'];
        $role = $request->input('role');
        $role_perms = (array) $request->input('role_perms');
        DB::beginTransaction();
        try{
            $role = Role::create($role);
            $role->perms()->sync($role_perms);
            DB::commit();
            if($backtype==0){
                return Redirect::to(action('Admin\RoleController@index'));
            }else{
                return redirect()->action('Admin\RoleController@edit', [$role->id]);
            }
        }catch(\Exception $e){
            DB::rollback();
            dd('33', $e);
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
        $role = Role::find($id);
        $checks = [];
        foreach($role->perms as $perm){
            $checks[] = $perm->id;
        }
        $perms = Permission::all()->sortBy("name");
        return view('admin.role.edit', ['role' => $role,
                                        'checks' => $checks,
                                        'perms' => $perms]);
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
        $validator = Validator::make(
            $request->all(), [
                'roles.*.name' => 'required|unique:roles|max:255',
                'roles.*.display_name' => 'required'
            ]);

        if ($validator->fails()) {
            return redirect(action('Admin\RoleController@create'))
                ->withErrors($validator)
                ->withInput();
        }

        $backtype =(int) $request['other']['backtype'];
        $arole = $request->input('role');
        $role_perms = (array) $request->input('role_perms');
        DB::beginTransaction();
        try{
            $role = Role::find($id);
            $role->update($arole);
            $role->perms()->sync($role_perms);
            DB::commit();
            if($backtype==0){
                return Redirect::to(action('Admin\RoleController@index'));
            }else{
                return redirect()->action('Admin\RoleController@edit', [$role->id]);
            }
        }catch(\Exception $e){
            DB::rollback();
            dd($e);
        }
    }

    public function refreshRolesPerms(){
        $confRoles = Config::get('entrust.base_roles');
        $confPerms = Config::get('entrust.base_perms');
        $roles = [];
        $perms = [];
        foreach($confRoles as $role){
            $r = [
                'id' => $role[0],
                'name' => $role[1],
                'display_name' => $role[2],
                'description' => $role[3],
            ];
            $role = Role::find($r['id']);
            if($role){
                $role->update($r);
            }else{
                Role::create($r);
            }
        }
        foreach($confPerms as $perm){
            $p = [
                'id' => $perm[0],
                'name' => $perm[1],
                'display_name' => $perm[2],
                'description' => $perm[3],
            ];
            $perm = Permission::find($p['id']);
            if($perm){
                $perm->update($p);
            }else{
                Permission::create($p);
            }
        }
        return back();
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

        $count = Role::all()->count();
        $contents = Role::skip($start)->take($length)->get();
        Log::info('count::'.$count);
        $json = ['recordsTotal' => $count,
                 'recordsFiltered' => $count,
                 'draw' => $draw,
                 'data' => $contents];

        Log::info(response()->json($json));
        return response()->json($json);
    }

}
