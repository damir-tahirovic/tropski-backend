<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Category extends Model implements HasMedia
{
    use HasFactory;

    use InteractsWithMedia;

    protected $fillable = [
        'main_cat_id',
        'category_id'
    ];

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
