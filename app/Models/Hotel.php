<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

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
 *     ),
 *     @OA\Property(
 *         property="description",
 *         type="string",
 *         description="The description of the hotel"
 *     ),
 *     @OA\Property(
 *         property="primary_color",
 *         type="string",
 *         description="The primary color of the hotel"
 *     ),
 *     @OA\Property(
 *         property="primary_color_light",
 *         type="string",
 *         description="The light variant of the primary color of the hotel"
 *     ),
 *     @OA\Property(
 *         property="primary_color_dark",
 *         type="string",
 *         description="The dark variant of the primary color of the hotel"
 *     ),
 *     @OA\Property(
 *         property="secondary_color",
 *         type="string",
 *         description="The secondary color of the hotel"
 *     ),
 *     @OA\Property(
 *         property="secondary_color_light",
 *         type="string",
 *         description="The light variant of the secondary color of the hotel"
 *     ),
 *     @OA\Property(
 *         property="secondary_color_dark",
 *         type="string",
 *         description="The dark variant of the secondary color of the hotel"
 *     ),
 *     @OA\Property(
 *         property="banner_text",
 *         type="string",
 *         description="The banner text of the hotel"
 *     )
 * )
 */
class Hotel extends Model implements HasMedia
{
    use HasFactory;

    use HasFactory;

    use InteractsWithMedia;

    protected $fillable = [
        "name",
        "his_id",
        "description",
        "primary_color",
        "primary_color_light",
        "primary_color_dark",
        "secondary_color",
        "secondary_color_light",
        "secondary_color_dark",
        "banner_text",
    ];

    protected $hidden = ['created_at', 'updated_at'];

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

    public function hotelLanguages()
    {
        return $this->hasMany(HotelLanguage::class);
    }

    public function languages()
    {
        return $this->hasManyThrough(
            Language::class,
            HotelLanguage::class,
            'hotel_id',
            'id',
            'id',
            'lang_id'
        );
    }

}
