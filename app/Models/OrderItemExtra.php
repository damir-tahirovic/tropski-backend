<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItemExtra extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_item_id',
        'extra_id',
    ];

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function extra()
    {
        return $this->belongsTo(Extra::class);
    }
}
