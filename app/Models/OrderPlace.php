<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="OrderPlace",
 *     required={"hotel_id", "main_cat_id", "name", "code", "reported"},
 *     @OA\Property(
 *         property="hotel_id",
 *         type="integer",
 *         description="The ID of the hotel"
 *     ),
 *     @OA\Property(
 *         property="main_cat_id",
 *         type="integer",
 *         description="The ID of the main category"
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="The name of the order place"
 *     ),
 *     @OA\Property(
 *         property="code",
 *         type="string",
 *         description="The code of the order place"
 *     ),
 *     @OA\Property(
 *          property="reported",
 *          type="boolean",
 *          description="Whether the order place is reported or not"
 *      )
 * )
 */
class OrderPlace extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'reported',
        'hotel_id',
        'main_cat_id'
    ];

    protected $hidden = ['created_at', 'updated_at'];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function mainCategory()
    {
        return $this->belongsTo(MainCategory::class);
    }

}
