<?php

namespace Modules\Pinjaman\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Jaminan extends Model
{
    use HasFactory;

    protected $table = 'jaminan';

    protected $fillable = [
        'nama',
        'deskripsi',
        'status',
    ];
    
    protected static function newFactory()
    {
        return \Modules\Pinjaman\Database\factories\JaminanFactory::new();
    }

    public function skemaPinjaman()
    {
        return $this->belongsToMany(
            SkemaPinjaman::class,
            'skema_jaminan',
            'id_jaminan',
            'id_skema_pinjaman'
        );
    }

    public function pengajuanPinjaman()
    {
        return $this->belongsToMany(
            PengajuanPinjaman::class,
            'pengajuan_jaminan',
            'id_jaminan',
            'id_pengajuan'
        );
    }
}
