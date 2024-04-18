<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'main_cat_id'
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
        return $this->belongsTo(MainCategory::class);
    }
}
