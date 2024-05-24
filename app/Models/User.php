<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;

//use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'superadmin',
        'password'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = ['created_at', 'updated_at', 'password'];

    // /**
    //  * The attributes that should be cast.
    //  *
    //  * @var array<string, string>
    //  */
    // protected $casts = [
    //     'email_verified_at' => 'datetime',
    // ];

    public function hotelUsers()
    {
        return $this->hasMany(HotelUser::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function roles()
    {
        $roles = DB::table('users')
            ->join('hotel_users', 'users.id', '=', 'hotel_users.user_id')
            ->join('role_hotel_users', 'hotel_users.id', '=', 'role_hotel_users.hotel_user_id')
            ->join('roles', 'role_hotel_users.role_id', '=', 'roles.id')
            ->where('users.id', "=", $this->id)
            ->select('roles.name')
            ->get();
        return $roles;
    }

}
