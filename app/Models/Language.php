<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        return $this->hasMany(ItemTypeTran::class);
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
}
