<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class timeLine extends Model
{
    use HasFactory;
    protected $fillable = [
        'nama', 
        'waktu_mulai',
        'waktu_berakhir',
        'status',
    ];
}
