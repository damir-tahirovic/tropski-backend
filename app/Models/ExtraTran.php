<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="ExtraTran",
 *     required={"extra_id", "lang_id", "name"},
 *     @OA\Property(
 *         property="extra_id",
 *         type="integer",
 *         description="The ID of the extra"
 *     ),
 *     @OA\Property(
 *         property="lang_id",
 *         type="integer",
 *         description="The ID of the language"
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="The name of the extra in the specified language"
 *     )
 * )
 */

class ExtraTran extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'extra_id',
        'lang_id'
    ];

    protected $hidden = ['created_at', 'updated_at'];

    public function language()
    {
        return $this->belongsTo(Language::class);
    }
    public function extra()
    {
        return $this->belongsTo(Extra::class);
    }
}
