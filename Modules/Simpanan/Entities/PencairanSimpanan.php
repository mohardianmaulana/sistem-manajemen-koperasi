<?php

namespace Modules\Simpanan\Entities;

use App\Models\Core\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class PencairanSimpanan extends Model
{
    use HasFactory;

    protected $table = 'pencairan_simpanan';

    protected $fillable = [
        'kode_pencairan',
        'nominal_pencairan',
        'alasan',
        'status',
        'catatan',
        'bukti_transfer',
        'tanggal_verifikasi',
        'tanggal_pencairan',
        'id_anggota',
        'id_verifikator',
        'id_bendahara',
    ];

     protected $casts = [
        'nominal_pencairan' => 'integer',
        'tanggal_verifikasi' => 'datetime',
        'tanggal_pencairan' => 'datetime',
    ];

    public const STATUS_PENDING = 'pending';
    public const STATUS_DIVERIFIKASI = 'diverifikasi';
    public const STATUS_DITOLAK = 'ditolak';
    public const STATUS_DICAIRKAN = 'dicairkan';
    public const STATUS_GAGAL = 'gagal';

    public function anggota()
    {
        return $this->belongsTo(User::class, 'id_anggota');
    }

    public function verifikator()
    {
        return $this->belongsTo(User::class, 'id_verifikator');
    }

    public function bendahara()
    {
        return $this->belongsTo(User::class, 'id_bendahara');
    }


    protected static function newFactory()
    {
        return \Modules\Simpanan\Database\factories\PencairanSimpananFactory::new();
    }
}
