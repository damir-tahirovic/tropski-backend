<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @OA\Schema(
 *     schema="MainCategory",
 *     required={"name", "hotel_id"},
 *     @OA\Property(
 *         property="hotel_id",
 *         type="integer",
 *         description="The ID of the hotel that the main category belongs to"
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="The name of the main category"
 *     ),
 * )
 */
class MainCategory extends Model implements HasMedia
{
    use HasFactory;

    use InteractsWithMedia;

    protected $fillable = [
        'hotel_id'
    ];

    protected $hidden = ['created_at', 'updated_at'];

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

    public function items()
    {
        return $this->hasManyThrough(
            Item::class,
            Category::class,
            'main_cat_id',
            'category_id',
            'id',
            'id'
        );
    }

//    public function itemTypes()
//    {
//        $itemTypes = DB::table('main_categories as mc')
//            ->join('categories as c', 'c.main_cat_id', '=', 'mc.id')
//            ->join('items as i', 'i.category_id', '=', 'c.id')
//            ->join('item_types as it', 'it.item_id', '=', 'i.id')
//            ->select('it.id', 'it.price', 'it.quantity', 'it.unit')
//            ->get();
//        return $itemTypes;
//    }

}
