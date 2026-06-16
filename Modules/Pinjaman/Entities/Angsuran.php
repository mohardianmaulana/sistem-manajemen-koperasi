<?php

namespace Modules\Pinjaman\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Angsuran extends Model
{
    use HasFactory;

    protected $table = 'angsuran';

    protected $fillable = [
        'id_pinjaman',
        'angsuran_ke',
        'jumlah_angsuran',
        'tanggal_jatuh_tempo',
        'status_bayar',
    ];
    
    protected static function newFactory()
    {
        return \Modules\Pinjaman\Database\factories\AngsuranFactory::new();
    }

    public function pinjaman()
    {
        return $this->belongsTo(Pinjaman::class, 'id_pinjaman');
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'id_angsuran');
    }
}
