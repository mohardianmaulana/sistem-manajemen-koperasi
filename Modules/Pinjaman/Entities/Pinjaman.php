<?php

namespace Modules\Pinjaman\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pinjaman extends Model
{
    use HasFactory;

    protected $table = 'pinjaman';

    protected $fillable = [
        'id_pengajuan',
        'tanggal_disetujui',
        'jumlah_disetujui',
        'jumlah_bunga',
        'total_pinjaman',
        'status_pinjaman',
    ];
    
    protected static function newFactory()
    {
        return \Modules\Pinjaman\Database\factories\PinjamanFactory::new();
    }

    public function pengajuan()
    {
        return $this->belongsTo(PengajuanPinjaman::class, 'id_pengajuan');
    }

    public function angsuran()
    {
        return $this->hasMany(Angsuran::class, 'id_pinjaman');
    }
}
