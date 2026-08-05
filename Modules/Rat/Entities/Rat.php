<?php

namespace Modules\Rat\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rat extends Model
{
    use HasFactory;
    protected $table = 'rat';

    protected $fillable = [
        'tahun',
        'tanggal_rat',
        'status',
    ];

    protected $casts = [
        'tanggal_rat' => 'date',
    ];

    public const STATUS_BELUM = 'belum';

    public const STATUS_SELESAI = 'selesai';
    
    protected static function newFactory()
    {
        return \Modules\Rat\Database\factories\RatFactory::new();
    }
}
