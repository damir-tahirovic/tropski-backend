<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="ExtraGroup",
 *     required={"hotel_id", "name"},
 *     @OA\Property(
 *         property="hotel_id",
 *         type="integer",
 *         description="The ID of the hotel"
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="The name of the extra group"
 *     )
 * )
 */

class ExtraGroup extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'hotel_id'
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function extraGroupPivots()
    {
        return $this->hasMany(ExtraGroupExtraPivot::class);
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
