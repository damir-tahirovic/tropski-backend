<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="ItemType",
 *     required={"item_id", "quantity", "unit", "price"},
 *     @OA\Property(
 *         property="item_id",
 *         type="integer",
 *         description="The ID of the item"
 *     ),
 *     @OA\Property(
 *         property="quantity",
 *         type="integer",
 *         description="The quantity of the item type"
 *     ),
 *     @OA\Property(
 *         property="unit",
 *         type="string",
 *         description="The unit of the item type"
 *     ),
 *     @OA\Property(
 *         property="price",
 *         type="number",
 *         format="float",
 *         description="The price of the item type"
 *     )
 * )
 */

class ItemType extends Model
{
    use HasFactory;

    protected $fillable = [
        'unit',
        'price',
        'quantity',
        'item_id'
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function itemTypeTrans()
    {
        return $this->hasMany(ItemTypeTran::class);
    }
}
