<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @OA\Schema(
 *     schema="Category",
 *     required={"name_en", "name_me", "main_cat_id"},
 *     @OA\Property(
 *         property="name_en",
 *         type="string",
 *         description="The English name of the category"
 *     ),
 *     @OA\Property(
 *         property="name_me",
 *         type="string",
 *         description="The Montenegrin name of the category"
 *     ),
 *     @OA\Property(
 *         property="main_cat_id",
 *         type="integer",
 *         description="The ID of the main category"
 *     ),
 *     @OA\Property(
 *         property="image",
 *         type="string",
 *         format="binary",
 *         description="The image of the category"
 *     )
 * )
 */
class Category extends Model implements HasMedia
{
    use HasFactory;

    use InteractsWithMedia;

    protected $fillable = [
        'main_cat_id',
        'category_id'
    ];

    protected $hidden = ['created_at', 'updated_at'];

    public function categoryTrans()
    {
        return $this->hasMany(CategoryTran::class);
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function mainCategory()
    {
        return $this->belongsTo(MainCategory::class, 'main_cat_id');
    }

    public function parentCategory()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function allSubcategories()
    {
        return $this->subcategories()->with('allSubcategories');
    }

    public function subCategories()
    {
        return $this->hasMany(Category::class, 'category_id');
    }

}
