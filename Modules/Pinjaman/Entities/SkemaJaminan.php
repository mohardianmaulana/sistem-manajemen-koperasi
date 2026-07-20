<?php

namespace Modules\Pinjaman\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SkemaJaminan extends Model
{
    use HasFactory;

    protected $table = 'skema_jaminan';

    protected $fillable = [
        'id_skema_pinjaman',
        'id_jaminan',
    ];
    
    protected static function newFactory()
    {
        return \Modules\Pinjaman\Database\factories\SkemaPinjamanFactory::new();
    }
}
