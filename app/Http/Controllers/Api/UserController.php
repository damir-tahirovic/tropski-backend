<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\HotelUser;
use App\Models\Role;
use App\Models\RoleHotelUser;
use Exception;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
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
        //        try {
//            $this->authorize('create', User::class);
//        } catch (Exception $e) {
//            return response()->json($e->getMessage(), 401);
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
            $role = DB::table('roles')->where('id', $request->input('role_id'))->first();
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
                return response()->json(['error' => "Username or password incorrect."], 401);
            }

            $user = Auth::user();
            $token = $user->createToken('API TOKEN')->plainTextToken;

            return response()->json([
                'message' => 'User login successfully',
                'token' => $token,
                'username' => $user->username,
                'roles' => $user->roles()
            ], 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()],);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()],);
        }
    }

    public function logoutUser(Request $request)
    {
//        try {
//            $this->authorize('view', User::class);
//        } catch (Exception $e) {
//            return response()->json($e->getMessage(), 401);
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
    function update(Request $request, $id)
    {
        try {

            $request->validate([
                'hotel_id' => 'required',
                'role_id' => 'required',
                'username' => 'required|max:255|unique:users,username,' . $id,
                'password' => 'required',
            ]);

            $user = User::findOrFail($id);
            $hotel = Hotel::findOrFail($request->input('hotel_id'));
            $role = Role::findOrFail($request->input('role_id'));
            if (!Hash::check($request->input('old_password'), $user->password)) {
                return response()->json(['message' => 'User with that password does not exist'], 400);
            }


            $user->hotel_id = $request->input('hotel_id');
            $user->role_id = $request->input('role_id');
            $user->username = $request->input('username');
            $user->password = Hash::make($request->input('password'));

            $user->save();

            return response()->json(['message' => 'User updated successfully'], 200);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
    }

    public function index()
    {
        try {
            $users = User::with([
                'hotelUsers.hotel' => function ($query) {
                    $query->select('id', 'name');
                },
            ])->get();

            $users = $users->map(function ($user) {
                $hotels = $user->hotelUsers->map(function ($hotelUser) {
                    return [
                        'hotel_id' => $hotelUser->hotel->id,
                        'hotel_name' => $hotelUser->hotel->name,
                    ];
                });

                unset($user->hotelUsers);

                $user->hotels = $hotels;
                $user->roles = $user->roles();

                return $user;
            });

            return response()->json(['users' => $users], 200);
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }


    public function usersByHotel($id)
    {
        try {
            $users = User::whereHas('hotelUsers', function ($query) use ($id) {
                $query->where('hotel_id', $id);
            })->with([
                'hotelUsers.hotel' => function ($query) {
                    $query->select('id', 'name');
                },
            ])->get();

            $users = $users->map(function ($user) {
                $hotels = $user->hotelUsers->map(function ($hotelUser) {
                    return [
                        'hotel_id' => $hotelUser->hotel->id,
                        'hotel_name' => $hotelUser->hotel->name,
                    ];
                });

                unset($user->hotelUsers);

                $user->hotels = $hotels;
                $user->roles = $user->roles();

                return $user;
            });

            return response()->json(['users' => $users], 200);
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }


    public
    function userRole($id)
    {
        //        try {
//            $this->authorize('viewAny', User::class);
//        } catch (Exception $e) {
//            return response()->json($e->getMessage(), 401);
        try {
            $user = User::findOrFail($id);
            $data = $user->roles();
            return response()->json(['roles' => $data], 200);
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }


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
