<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\TrackUser;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class Odc extends Model implements Auditable 
{
    use AuditableTrait, SoftDeletes, TrackUser;

    protected $fillable = [
        'name',
        'mikrotik_id',
    ];
    protected $casts = [
        'name' => 'string',
        'mikrotik_id' => 'string',
    ];

    public function odps()
    {
        return $this->hasMany(Odp::class);
    }

    public function mikrotik()
    {
        return $this->belongsTo(Mikrotik::class);
    }

}
