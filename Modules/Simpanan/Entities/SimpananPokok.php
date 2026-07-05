<?php

namespace Modules\Simpanan\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Pinjaman\Entities\Anggota;

class SimpananPokok extends Model
{
    use HasFactory;
    protected $table = 'tabungan';
    protected $fillable = [
        'nilai',
        'tanggal',
        'bukti',
        'status',
        'id_anggota',
    ];
    
    protected static function newFactory()
    {
        return \Modules\Simpanan\Database\factories\TabunganFactory::new();
    }

    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'id_anggota');
    }
}
