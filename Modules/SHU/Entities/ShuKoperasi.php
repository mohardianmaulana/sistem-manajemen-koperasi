<?php

namespace Modules\SHU\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ShuKoperasi extends Model
{
    use HasFactory;

    protected $table = 'sisa_hasil_usaha';
    protected $fillable = [
        'jasa_simpanan',
        'jasa_pinjaman',
        'dana_cadangan',
        'jasa_pengurus',
        'dana_sosial',
        'total_shu',
        'tahun',
    ];
    
    protected static function newFactory()
    {
        return \Modules\SHU\Database\factories\ShuKoperasiFactory::new();
    }
}
