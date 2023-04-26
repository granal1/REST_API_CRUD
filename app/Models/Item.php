<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $table = "item";

    protected $fillable = [
        'name',
        'phone',
        'key',
        'history',
        'created_at',
        'updated_at'
    ];

}
