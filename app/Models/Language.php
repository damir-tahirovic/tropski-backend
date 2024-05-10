<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Language",
 *     required={"name", "code"},
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="The name of the language"
 *     ),
 *     @OA\Property(
 *         property="code",
 *         type="string",
 *         description="The code of the language"
 *     )
 * )
 */

class Language extends Model
{
    use HasFactory;

    protected $fillable = [
        "name", "code"
    ];

    public function itemTrans()
    {
        return $this->hasMany(ItemTran::class);
    }

    public function itemTypeTrans()
    {
        return $this->hasMany(ItemTypeTran::class, 'lang_id');
    }

    public function categoryTrans()
    {
        return $this->hasMany(CategoryTran::class);
    }

    public function mainCategoryTrans()
    {
        return $this->hasMany(MainCategoryTran::class);
    }

    public function extraTrans()
    {
        return $this->hasMany(ExtraTran::class);
    }

    public function hotelLanguages()
    {
        return $this->hasMany(HotelLanguage::class);
    }

}
