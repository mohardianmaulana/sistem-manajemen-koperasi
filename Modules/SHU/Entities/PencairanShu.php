<?php

namespace Modules\SHU\Entities;

use App\Models\Core\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PencairanShu extends Model
{
    use HasFactory;

     protected $table = 'pencairan_shu';

    protected $fillable = [
    'id_shu_anggota',
    'nominal_pengajuan',
    'tanggal_pengajuan',
    'tanggal_persetujuan',
    'tanggal_pencairan',
    'status',
    'keterangan',
    'disetujui_oleh',
    'bukti'
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

    /**
     * Pengurus yang menyetujui pencairan.
     */
    public function approver()
    {
        return $this->belongsTo(
            User::class,
            'disetujui_oleh'
        );
    }

    public const STATUS_BELUM_DIAJUKAN = 'belum_diajukan';
    public const STATUS_MENUNGGU = 'menunggu';
    public const STATUS_DISETUJUI = 'disetujui';
    public const STATUS_DITOLAK = 'ditolak';
    public const STATUS_DICAIRKAN = 'dicairkan';

     public function scopeBelumDiajukan($query)
    {
        return $query->where('status', self::STATUS_BELUM_DIAJUKAN);
    }

    public function scopeMenunggu($query)
    {
        return $query->where('status', self::STATUS_MENUNGGU);
    }

    public function scopeDisetujui($query)
    {
        return $query->where('status', self::STATUS_DISETUJUI);
    }

    public function scopeDitolak($query)
    {
        return $query->where('status', self::STATUS_DITOLAK);
    }

    public function scopeDicairkan($query)
    {
        return $query->where('status', self::STATUS_DICAIRKAN);
    }

    public function getStatusBadgeAttribute()
    {
        return match ($this->status) {
            self::STATUS_BELUM_DIAJUKAN =>
                '<span class="badge badge-secondary">Belum Diajukan</span>',

            self::STATUS_MENUNGGU =>
                '<span class="badge badge-warning">Menunggu</span>',

            self::STATUS_DISETUJUI =>
                '<span class="badge badge-primary">Disetujui</span>',

            self::STATUS_DITOLAK =>
                '<span class="badge badge-danger">Ditolak</span>',

            self::STATUS_DICAIRKAN =>
                '<span class="badge badge-success">Dicairkan</span>',

            default =>
                '<span class="badge badge-dark">Tidak Diketahui</span>',
        };
    }

    
    protected static function newFactory()
    {
        return \Modules\SHU\Database\factories\PencairanShuFactory::new();
    }
}
