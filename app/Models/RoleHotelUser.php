<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="RoleHotelUser",
 *     required={"role_id", "hotel_user_id"},
 *     @OA\Property(
 *         property="role_id",
 *         type="integer",
 *         description="The ID of the role"
 *     ),
 *     @OA\Property(
 *         property="hotel_user_id",
 *         type="integer",
 *         description="The ID of the hotel user"
 *     )
 * )
 */

class RoleHotelUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_user_id',
        'role_id'
    ];

    protected $hidden = ['created_at', 'updated_at'];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    public function hotelUser()
    {
        return $this->belongsTo(HotelUser::class, 'hotel_user_id', 'id');
    }

}
