<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="HotelUser",
 *     required={"user_id", "hotel_id"},
 *     @OA\Property(
 *         property="user_id",
 *         type="integer",
 *         description="The ID of the user"
 *     ),
 *     @OA\Property(
 *         property="hotel_id",
 *         type="integer",
 *         description="The ID of the hotel"
 *     )
 * )
 */
class HotelUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_id',
        'user_id'
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'hotel_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

}
