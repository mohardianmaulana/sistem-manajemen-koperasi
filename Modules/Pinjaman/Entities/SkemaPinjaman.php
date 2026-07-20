<?php

namespace Modules\Pinjaman\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SkemaPinjaman extends Model
{
    use HasFactory;

    protected $table = 'skema_pinjaman';

    protected $fillable = [
        'nama',
        'min_nominal',
        'max_nominal',
        'min_tenor',
        'max_tenor',
        'bunga',
        'jaminan',
        'deskripsi',
        'status',
    ];
    
    protected static function newFactory()
    {
        return \Modules\Pinjaman\Database\factories\SkemaPinjamanFactory::new();
    }

    public function pengajuanPinjaman()
    {
        return $this->hasMany(PengajuanPinjaman::class, 'id_skema_pinjaman');
    }

    public function daftarJaminan()
    {
        return $this->belongsToMany(
            Jaminan::class,
            'skema_jaminan',
            'id_skema_pinjaman',
            'id_jaminan'
        );
    }
}
