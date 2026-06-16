<?php

namespace Modules\Pinjaman\Entities;

use App\Models\Core\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PengajuanPinjaman extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_pinjaman';

    protected $fillable = [
        'id_anggota',
        'id_skema_pinjaman',
        'jumlah_pengajuan',
        'lama_angsuran',
        'tanggal_pengajuan',
        'status_pengajuan',
        'no_hp',
        'no_ktp',
        'no_rekening',
        'alamat',
        'nama_istri_suami',
        'path_form_pinjaman',
        'path_dokumen',
    ];
    
    protected static function newFactory()
    {
        return \Modules\Pinjaman\Database\factories\PengajuanPinjamanFactory::new();
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'id_anggota');
    }

    public function skemaPinjaman()
    {
        return $this->belongsTo(SkemaPinjaman::class, 'id_skema_pinjaman');
    }

    public function persetujuan()
    {
        return $this->hasMany(Persetujuan::class, 'id_pengajuan');
    }

    public function pinjaman()
    {
        return $this->hasOne(Pinjaman::class, 'id_pengajuan');
    }
}
