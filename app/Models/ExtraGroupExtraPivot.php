<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="ExtraGroupExtraPivot",
 *     required={"extra_group_id", "extra_id", "price", "quantity", "unit"},
 *     @OA\Property(
 *         property="extra_group_id",
 *         type="integer",
 *         description="The ID of the extra group"
 *     ),
 *     @OA\Property(
 *         property="extra_id",
 *         type="integer",
 *         description="The ID of the extra"
 *     ),
 *     @OA\Property(
 *         property="price",
 *         type="number",
 *         format="float",
 *         description="The price of the extra in the group"
 *     ),
 *     @OA\Property(
 *         property="quantity",
 *         type="integer",
 *         description="The quantity of the extra in the group"
 *     ),
 *     @OA\Property(
 *         property="unit",
 *         type="string",
 *         description="The unit of the extra in the group"
 *     )
 * )
 */
class ExtraGroupExtraPivot extends Model
{
    use HasFactory;

    protected $fillable = [
        'unit',
        'price',
        'quantity',
        'extra_group_id',
        'extra_id'
    ];

    protected $hidden = ['created_at', 'updated_at'];

    public function extra()
    {
        return $this->belongsTo(Extra::class);
    }
    public function extra_group()
    {
        return $this->belongsTo(ExtraGroup::class);
    }
}
