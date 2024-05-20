<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @OA\Schema(
 *     schema="Extra",
 *     required={"hotel_id", "image"},
 *     @OA\Property(
 *         property="hotel_id",
 *         type="integer",
 *         description="The ID of the hotel"
 *     ),
 *     @OA\Property(
 *         property="image",
 *         type="string",
 *         format="binary",
 *         description="The image of the extra"
 *     )
 * )
 */

class Extra extends Model implements HasMedia
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

    public function extraGroupExtraPivots()
    {
        return $this->hasMany(ExtraGroupExtraPivot::class);
    }

    public function extraTrans()
    {
        return $this->hasMany(ExtraTran::class);
    }
}
