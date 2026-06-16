<?php

namespace Modules\Pinjaman\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayaran';

    protected $fillable = [
        'id_angsuran',
        'jenis_pembayaran',
        'tanggal_bayar',
        'jumlah_bayar',
        'bukti_pembayaran',
        'status_pembayaran',
        'catatan',
    ];
    
    protected static function newFactory()
    {
        return \Modules\Pinjaman\Database\factories\PembayaranFactory::new();
    }

    public function angsuran()
    {
        return $this->belongsTo(Angsuran::class, 'id_angsuran');
    }
}
