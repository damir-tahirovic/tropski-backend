<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExtraTran extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'extra_id',
        'lang_id'
    ];

    public function language()
    {
        return $this->belongsTo(Language::class);
    }
    public function extra()
    {
        return $this->belongsTo(Extra::class);
    }
}
