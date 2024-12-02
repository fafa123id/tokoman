<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modal extends Model
{
    use HasFactory;
    protected $table = "Modal";
    protected $fillable = [
        "Total_modal","Tanggal"
    ];
}
