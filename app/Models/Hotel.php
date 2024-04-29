<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Hotel",
 *     required={"name", "his_id"},
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="The name of the hotel"
 *     ),
 *     @OA\Property(
 *         property="his_id",
 *         type="integer",
 *         description="The HIS ID of the hotel"
 *     )
 * )
 */
class Hotel extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "his_id",
        "description",
    ];

    public function hotelUsers()
    {
        return $this->hasMany(HotelUser::class);
    }

    public function orderPlaces()
    {
        return $this->hasMany(OrderPlace::class);
    }

    public function mainCategories()
    {
        return $this->hasMany(MainCategory::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function extras()
    {
        return $this->hasMany(Extra::class);
    }

    public function extraGroups()
    {
        return $this->hasMany(ExtraGroup::class);
    }


}
