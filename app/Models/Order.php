<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_id',
        'ordered_from_id',
        'order_datetime',
        'total_price',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function orderPlace()
    {
        return $this->belongsTo(OrderPlace::class);
    }

    public function orderItemTypes()
    {
        return $this->hasMany(OrderItem::class);
    }

}
