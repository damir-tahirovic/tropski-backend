<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="ItemTran",
 *     required={"item_id", "lang_id", "name", "description"},
 *     @OA\Property(
 *         property="item_id",
 *         type="integer",
 *         description="The ID of the item"
 *     ),
 *     @OA\Property(
 *         property="lang_id",
 *         type="integer",
 *         description="The ID of the language"
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="The name of the item in the specified language"
 *     ),
 *     @OA\Property(
 *         property="description",
 *         type="string",
 *         description="The description of the item in the specified language"
 *     )
 * )
 */
class ItemTran extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'item_id',
        'lang_id'
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function languages()
    {
        return $this->belongsTo(Language::class);
    }


}
