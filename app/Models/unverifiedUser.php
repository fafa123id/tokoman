<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class unverifiedUser extends Model
{
    use HasFactory;
    protected $table='unverifiedUser';
    protected $fillable = [
        "email",
        "token",
        "id_user"
    ];
}
