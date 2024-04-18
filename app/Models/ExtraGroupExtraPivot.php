<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExtraGroupExtraPivot extends Model
{
    use HasFactory;

    protected $fillable = [
        'unit',
        'price',
        'quantity',
        'extra_group_id',
        'extra_id'
    ];

    public function extra()
    {
        return $this->belongsTo(Extra::class);
    }
    public function extra_group()
    {
        return $this->belongsTo(ExtraGroup::class);
    }
}
