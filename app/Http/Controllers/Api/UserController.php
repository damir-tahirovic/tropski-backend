<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HotelUser;
use App\Models\RoleHotelUser;
use Exception;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    /**
     * Create User
     * @param Request $request
     * @return User
     */

    public function createUser(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'username' => 'required|unique:users',
                'password' => 'required',
                'role_id' => 'required',
                'hotel_id' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 401);
            }

            $input = $request->all();
            $input['password'] = Hash::make($input['password']);
            $user = User::create($input);
            $hotelUser = HotelUser::create([
                'hotel_id' => $request->input('hotel_id'),
                'user_id' => $user->id
            ]);

            $roleHotelUser = RoleHotelUser::create([
                'role_id' => $request->input('role_id'),
                'hotel_user_id' => $hotelUser->id
            ]);
            $token = $user->createToken('API TOKEN')->plainTextToken;
            return response()->json([
                'message' => 'User created successfully',
                'user' => $user,
                'token' => $token
            ], 201);
            return response()->json(['success' => $success], 201);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);

        }
    }

    /**
     * Login the User
     * @param Request $request
     * @return User
     */
    public function loginUser(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'username' => 'required',
                'password' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 401);
            }

            if (!Auth::attempt($request->all())) {
                return response()->json(['error' => "Username or password incorrect." ], 401);
            }

            $user = Auth::user();
            $token = $user->createToken('API TOKEN')->plainTextToken;
            return response()->json([
                'message' => 'User login successfully',
                'token' => $token,
                'roles' => $user->roles()
            ], 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()],);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()],);
        }
    }

//    public function logoutUser()
//    {
//        try {
//            Auth::user()->tokens()->delete();
//            return response()->json(['message' => 'User logout successfully'], 200);
//        } catch (Exception $e) {
//            return response()->json(['error' => $e->getMessage()],);
//        }
//    }

    public function logoutUser(Request $request)
    {
        try {
            if (Auth::check()) {
                $user = $request->user();
                if ($user) {
                    $user->tokens()->delete();
                    return response()->json(['message' => 'User logout successfully'], 200);
                } else {
                    return response()->json(['error' => 'No user is currently logged in'], 401);
                }
            } else {
                return response()->json(['error' => 'No user is currently logged in'], 401);
            }
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()],);
        }
    }

    public
    function index()
    {
        try {
            $users = User::all();
            return response()->json(['users' => $users], 200);
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }

    }


//    /**
//     * Display a listing of the resource.
//     *
//     * @return \Illuminate\Http\Response
//     */
//    public function index()
//    {
//        //
//    }
//
//    /**
//     * Store a newly created resource in storage.
//     *
//     * @param  \Illuminate\Http\Request  $request
//     * @return \Illuminate\Http\Response
//     */
//    public function store(Request $request)
//    {
//        //
//    }
//
//    /**
//     * Display the specified resource.
//     *
//     * @param  int  $id
//     * @return \Illuminate\Http\Response
//     */
//    public function show($id)
//    {
//        //
//    }
//
//    /**
//     * Update the specified resource in storage.
//     *
//     * @param  \Illuminate\Http\Request  $request
//     * @param  int  $id
//     * @return \Illuminate\Http\Response
//     */
//    public function update(Request $request, $id)
//    {
//        //
//    }
//
//    /**
//     * Remove the specified resource from storage.
//     *
//     * @param  int  $id
//     * @return \Illuminate\Http\Response
//     */
//    public function destroy($id)
//    {
//        //
//    }
}
