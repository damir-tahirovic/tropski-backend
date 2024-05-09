<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelLanguage extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_id',
        'lang_id'
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

}
