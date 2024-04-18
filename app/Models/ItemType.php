<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemType extends Model
{
    use HasFactory;

    protected $fillable = [
        'unit',
        'price',
        'quantity',
        'item_id'
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function itemTypeTrans()
    {
        return $this->hasMany(ItemTypeTran::class);
    }
}
