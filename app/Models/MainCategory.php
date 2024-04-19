<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class MainCategory extends Model implements HasMedia
{
    use HasFactory;
    
    use InteractsWithMedia;

    protected $fillable = [
        'hotel_id'
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function mainCategoryTrans()
    {
        return $this->hasMany(MainCategoryTran::class);
    }

    public function orderPlaces()
    {
        return $this->hasMany(OrderPlace::class);
    }

}
