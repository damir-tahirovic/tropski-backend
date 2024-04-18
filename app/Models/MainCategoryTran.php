<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        return $this->belongsTo(Language::class);
    }
}
