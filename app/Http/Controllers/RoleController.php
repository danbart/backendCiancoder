<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Validator;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return Role::all();
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
        $role = new Role();
        $validator = Validator::make($request->all(), [
            'role' => 'required|string|unique:role|min:4'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $role->role = $request->input('role');
        $response = $role->save();

        return response()->json(['response' => $response], 201);
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
        $role = Role::find($id);
        if (isset($role)) {
            return response()->json($role, 200);
        }
        return response()->json(['error' => 'No Exist'], 404);
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
        $role = Role::find($id);
        if (!isset($role)) {
            return response()->json(['error' => 'No Exist'], 404);
        }

        $validator = Validator::make($request->all(), [
            'role' => 'required|string|unique:role|min:4'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        $role->role = $request->input('role');
        $response = $role->update();

        return response()->json(['response' => $response], 200);
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
