<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleHotelUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_user_id',
        'role_id'
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    public function hotel_user()
    {
        return $this->belongsTo(HotelUser::class, 'hotel_user_id', 'id');
    }
    
}
