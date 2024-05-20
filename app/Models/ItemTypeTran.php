<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="ItemTypeTran",
 *     required={"item_type_id", "lang_id", "name"},
 *     @OA\Property(
 *         property="item_type_id",
 *         type="integer",
 *         description="The ID of the item type"
 *     ),
 *     @OA\Property(
 *         property="lang_id",
 *         type="integer",
 *         description="The ID of the language"
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="The name of the item type in the specified language"
 *     )
 * )
 */

class ItemTypeTran extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'lang_id',
        'item_type_id'
    ];

    protected $hidden = ['created_at', 'updated_at'];

    public function language()
    {
        return $this->belongsTo(Language::class, 'lang_id');
    }

    public function itemType()
    {
        return $this->belongsTo(ItemType::class);
    }

}
