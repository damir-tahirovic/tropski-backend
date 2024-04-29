<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @OA\Schema(
 *     schema="MainCategory",
 *     required={"hotel_id"},
 *     @OA\Property(
 *         property="hotel_id",
 *         type="integer",
 *         description="The ID of the hotel"
 *     )
 * )
 */

class MainCategory extends Model implements HasMedia
{
    use HasFactory;

    use InteractsWithMedia;

    protected $fillable = [
        'hotel_id'
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class, 'main_cat_id');
    }

    public function mainCategoryTrans()
    {
        return $this->hasMany(MainCategoryTran::class, 'main_cat_id');
    }

    public function orderPlaces()
    {
        return $this->hasMany(OrderPlace::class);
    }

}
