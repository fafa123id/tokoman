<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tokenRegister extends Model
{
    use HasFactory;
    protected $table = 'token_register';
    protected $fillable = [
        'token','role_id'];
}
