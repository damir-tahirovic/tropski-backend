<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Role",
 *     required={"name", "description"},
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="The name of the role"
 *     ),
 *     @OA\Property(
 *         property="description",
 *         type="string",
 *         description="The description of the role"
 *     )
 * )
 */
class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "description"
    ];

    public function roleHotelUsers()
    {
        return $this->hasMany(RoleHotelUser::class);
    }
}
