<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'extra_group_id',
        'category_id'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function extraGroup()
    {
        return $this->belongsTo(ExtraGroup::class);
    }

    public function itemTrans()
    {
        return $this->hasMany(ItemTran::class);
    }

    public function itemTypes()
    {
        return $this->hasMany(ItemType::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
