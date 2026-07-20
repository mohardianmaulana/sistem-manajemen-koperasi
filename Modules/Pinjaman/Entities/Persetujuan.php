<?php

namespace Modules\Pinjaman\Entities;

use App\Models\Core\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Persetujuan extends Model
{
    use HasFactory;

    protected $table = 'persetujuan';

    protected $fillable = [
        'id_pengajuan',
        'role',
        'disetujui_oleh',
        'status',
        'tanggal_disetujui',
        'catatan',
    ];
    
    protected static function newFactory()
    {
        return \Modules\Pinjaman\Database\factories\PersetujuanFactory::new();
    }

    public function pengajuan()
    {
        return $this->belongsTo(PengajuanPinjaman::class, 'id_pengajuan');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'disetujui_oleh');
    }
}
