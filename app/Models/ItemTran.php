<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemTran extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'item_id',
        'lang_id'
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function languages()
    {
        return $this->belongsTo(Language::class);
    }

    
}
