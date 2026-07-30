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
        'kode_pencairan',
        'id_shu_anggota',
        'nominal_pencairan',
        'tanggal_pencairan',
        'status',
        'keterangan',
        'dicairkan_oleh',
        'bukti',
    ];

     protected $casts = [
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
    public function pencair()
    {
        return $this->belongsTo(
            User::class,
            'dicairkan_oleh'
        );
    }

    public const STATUS_SIAP_DICAIRKAN = 'siap_dicairkan';
    public const STATUS_DICAIRKAN = 'dicairkan';
    public const STATUS_GAGAL = 'gagal';

    public function scopeSiapDicairkan($query)
    {
        return $query->where(
            'status',
            self::STATUS_SIAP_DICAIRKAN
        );
    }

    public function scopeDicairkan($query)
    {
        return $query->where(
            'status',
            self::STATUS_DICAIRKAN
        );
    }

    public function scopeGagal($query)
    {
        return $query->where(
            'status',
            self::STATUS_GAGAL
        );
    }
    public function getStatusBadgeAttribute()
    {
        return match ($this->status) {

            self::STATUS_SIAP_DICAIRKAN =>
                '<span class="badge badge-warning">Siap Dicairkan</span>',

            self::STATUS_DICAIRKAN =>
                '<span class="badge badge-success">Dicairkan</span>',

            self::STATUS_GAGAL =>
                '<span class="badge badge-danger">Gagal</span>',

            default =>
                '<span class="badge badge-dark">Tidak Diketahui</span>',
        };
    }
    
    protected static function newFactory()
    {
        return \Modules\SHU\Database\factories\PencairanShuFactory::new();
    }
}
