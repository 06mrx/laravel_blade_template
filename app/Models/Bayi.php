<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;
use App\Traits\TrackUser;
class Bayi extends Model implements Auditable
{
    use HasFactory, SoftDeletes, AuditableTrait, TrackUser;

    protected $table = 'tb_bayi'; // Sesuaikan dengan nama tabel kamu

    protected $fillable = [
        'id',
        'nama',
        'nik',
        'tgl_lahir',
        'jk',
        'nama_ortu',
        'bb',
        'tb',
        'll',
        'lk',
        'ket'
    ];

    public $incrementing = false; // Nonaktifkan auto-increment
    protected $keyType = 'string'; // Karena UUID adalah string

    //otomatis generate uuid pada kolom id saat creating
    protected static function booted(): void
    {
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

}