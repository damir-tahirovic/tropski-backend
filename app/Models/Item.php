<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @OA\Schema(
 *     schema="Item",
 *     required={"category_id", "extra_group_id", "code"},
 *     @OA\Property(
 *         property="category_id",
 *         type="integer",
 *         description="The ID of the category"
 *     ),
 *     @OA\Property(
 *         property="extra_group_id",
 *         type="integer",
 *         description="The ID of the extra group"
 *     ),
 *     @OA\Property(
 *         property="code",
 *         type="string",
 *         description="The code of the item"
 *     )
 * )
 */

class Item extends Model implements HasMedia
{
    use HasFactory;

    use InteractsWithMedia;

    protected $fillable = [
        'code',
        'extra_group_id',
        'category_id'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function extraGroup()
    {
        return $this->belongsTo(ExtraGroup::class);
    }

    public function itemTrans()
    {
        return $this->hasMany(ItemTran::class);
    }

    public function itemTypes()
    {
        return $this->hasMany(ItemType::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
