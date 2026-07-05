<?php

namespace Modules\SHU\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ShuAnggota extends Model
{
    use HasFactory;
    
    protected $table = 'shu_anggota';
    protected $fillable = [
        'id_anggota',
        'shu_anggota',
        'tanggal'
    ];
    
    protected static function newFactory()
    {
        return \Modules\SHU\Database\factories\ShuAnggotaFactory::new();
    }
}
