<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderPlace extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'reported',
        'hotel_id',
        'main_cat_id'
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function mainCategory()
    {
        return $this->belongsTo(MainCategory::class);
    }
    
}
