<?php

namespace Modules\SHU\Entities;

use App\Models\Core\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ShuAnggota extends Model
{
    use HasFactory;
    
    protected $table = 'shu_anggota';
    protected $fillable = [
        'shu_simpanan',
        'shu_pinjaman',
        'shu_anggota',
        'tanggal',
        'tahun',
        'id_anggota',
    ];
    
    protected static function newFactory()
    {
        return \Modules\SHU\Database\factories\ShuAnggotaFactory::new();
    }

    public function anggota()
    {
        return $this->belongsTo(User::class, 'id_anggota');
    }
}
