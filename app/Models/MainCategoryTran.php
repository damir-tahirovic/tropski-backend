<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="MainCategoryTran",
 *     required={"main_cat_id", "lang_id", "name"},
 *     @OA\Property(
 *         property="main_cat_id",
 *         type="integer",
 *         description="The ID of the main category"
 *     ),
 *     @OA\Property(
 *         property="lang_id",
 *         type="integer",
 *         description="The ID of the language"
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="The name of the main category in the specified language"
 *     )
 * )
 */

class MainCategoryTran extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'lang_id',
        'main_cat_id'
    ];

    public function mainCategories()
    {
        return $this->belongsTo(MainCategory::class);
    }

    public function languages()
    {
        return $this->belongsTo(Language::class, 'lang_id');
    }
}
