<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Extra extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_id'
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function extraGroupExtraPivots()
    {
        return $this->hasMany(ExtraGroupExtraPivot::class);
    }

    public function extraTrans()
    {
        return $this->hasMany(ExtraTran::class);
    }
}
