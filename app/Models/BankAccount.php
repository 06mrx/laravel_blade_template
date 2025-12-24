<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\TrackUser;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class BankAccount extends Model implements Auditable
{
    use AuditableTrait, SoftDeletes, TrackUser;

    protected $keyType = 'string';
    public $incrementing = false;

    // $table->uuid('id')->primary();
    //         $table->string('name');
    //         $table->string('owner');
    //         $table->string('account_number');
    //         $table->boolean('is_active');
    //         $table->softDeletes();
    //         $table->string('created_by');
    //         $table->string('updated_by');
    //         $table->tim
    protected $fillable = [
        'name',
        'owner',
        'account_number',    
        'is_active',
    ];
    protected $casts = [
        'id' => 'string',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->id) {
                $model->id = (string) \Str::uuid();
            }
        });
    }

   public function user() : BelongsTo {
       return $this->belongsTo(User::class, 'created_by');
   }
}
