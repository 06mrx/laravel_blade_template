<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\TrackUser;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class Odp extends Model implements Auditable 
{
    use AuditableTrait, SoftDeletes, TrackUser;

    protected $fillable = [
        'name',
        'odc_id'
    ];
    protected $casts = [
        'name' => 'string',
        'odc_id' => 'integer'
    ];

    public function odc()
    {
        return $this->belongsTo(Odc::class);
    }

}
