<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExtraGroup extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'hotel_id'
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function extraGroupPivots()
    {
        return $this->hasMany(ExtraGroupExtraPivot::class);
    }
    
    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
