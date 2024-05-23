<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class CategoryTran extends Model
{
    use HasFactory;

    /**
     * @OA\Schema(
     *     schema="CategoryTran",
     *     required={"category_id", "lang_id", "name"},
     *     @OA\Property(
     *         property="category_id",
     *         type="integer",
     *         description="The ID of the category"
     *     ),
     *     @OA\Property(
     *         property="lang_id",
     *         type="integer",
     *         description="The ID of the language"
     *     ),
     *     @OA\Property(
     *         property="name",
     *         type="string",
     *         description="The name of the category in the specified language"
     *     )
     * )
     */

    protected $fillable = [
        'name',
        'category_id',
        'lang_id'
    ];

    protected $hidden = ['created_at', 'updated_at'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function languages()
    {
        return $this->belongsTo(Language::class, 'lang_id');
    }
}
