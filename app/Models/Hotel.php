<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    use HasFactory;

    protected $fillable = [
        "name", 
        "his_id",
    ];

    public function hotelUsers()
    {
        return $this->hasMany(HotelUser::class);
    }

    public function orderPlaces()
    {
        return $this->hasMany(OrderPlace::class);
    }

    public function mainCategories()
    {
        return $this->hasMany(MainCategory::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function extras()
    {
        return $this->hasMany(Extra::class);
    }

    public function extraGroups()
    {
        return $this->hasMany(ExtraGroup::class);
    }

    
}
