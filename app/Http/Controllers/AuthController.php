<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Validator;

class AuthController extends Controller
{

    protected $roleuser = 'USER';

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * Get JWT credentials
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (!$token = auth()->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->createNewToken($token);
    }

    /**
     * Update user Info
     */

    public function update(Request $request)
    {
        //
        $user = User::find(auth()->user()->id);

        $validator = Validator::make($request->all(), [
            'name' => 'string|min:4',
            'last_name' => 'string|min:4',
            'document' => 'numeric',
            'addres' => 'string|between:10,200',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        if (!empty($request->input('name'))) $user->name = $request->input('name');
        if (!empty($request->input('last_name'))) $user->last_name = $request->input('last_name');
        if (!empty($request->input('document'))) $user->document = $request->input('document');
        if (!empty($request->input('addres'))) $user->addres = $request->input('addres');
        $response = $user->update();

        return response()->json(['response' => $response], 201);
    }

    /**
     * Register User
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nick_name' => 'required|string|between:5,20|unique:users',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|confirmed|min:6',
        ]);

        $role = DB::table('role')->select('id')->where('role', [$this->roleuser])->first();

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = User::create(array_merge(
            $validator->validated(),
            [
                'password' => bcrypt($request->password),
                'id_role' => $role->id
            ]
        ));

        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user
        ], 201);
    }

    /**
     * log out user
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'User successfully signed out']);
    }

    /**
     * Refresh a token.
     */
    public function refresh()
    {
        return $this->createNewToken(auth()->refresh());
    }

    /**
     * Get the authenticated User.
     */
    public function userProfile()
    {
        return response()->json(auth()->user());
    }

    /**
     * Get the token array structure.
     */
    protected function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }
}
