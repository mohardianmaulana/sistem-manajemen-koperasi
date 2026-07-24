<?php

namespace Modules\SHU\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PencairanShu extends Model
{
    use HasFactory;

     protected $table = 'pencairan_shu';

    protected $fillable = [
        'id_shu_anggota',
        'tanggal_pengajuan',
        'tanggal_persetujuan',
        'tanggal_pencairan',
        'status',
        'keterangan',
        'disetujui_oleh',
    ];

     protected $casts = [
        'tanggal_pengajuan' => 'date',
        'tanggal_persetujuan' => 'date',
        'tanggal_pencairan' => 'date',
    ];
    
     public function shuAnggota()
    {
        return $this->belongsTo(
            ShuAnggota::class,
            'id_shu_anggota'
        );
    }

    public function approver()
    {
        return $this->belongsTo(
            \App\Models\Core\User::class,
            'disetujui_oleh'
        );
    }

    protected static function newFactory()
    {
        return \Modules\SHU\Database\factories\PencairanShuFactory::new();
    }
}
