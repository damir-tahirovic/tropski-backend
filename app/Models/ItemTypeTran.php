<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemTypeTran extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'lang_id',
        'item_type_id'
    ];

    public function language()
    {
        return $this->belongsTo(Language::class, 'lang_id');
    }

    public function itemType()
    {
        return $this->belongsTo(ItemType::class);
    }

}
