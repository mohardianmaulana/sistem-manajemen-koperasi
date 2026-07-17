<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $table = 'units';
    protected $fillable = [
        'nama',
    ];
}