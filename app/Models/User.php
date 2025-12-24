<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\TrackUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use OwenIt\Auditing\Contracts\Auditable;
use \OwenIt\Auditing\Auditable as AuditableTrait;
<<<<<<< HEAD
use App\Traits\TrackUser;
=======
use Illuminate\Database\Eloquent\SoftDeletes;
>>>>>>> 74809ab (huh)

class User extends Authenticatable implements Auditable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
<<<<<<< HEAD
    use HasFactory, Notifiable, HasRoles, AuditableTrait, TrackUser;
=======
    use HasFactory, Notifiable, HasRoles, AuditableTrait, SoftDeletes, TrackUser;
>>>>>>> 74809ab (huh)

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    public $incrementing = false; // non-incrementing primary key
    protected $keyType = 'string'; // primary key bertipe string (UUID)
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_active',
        'plan_type',
        'subscription_start',
        'subscription_end',
        'trial_ends_at',
        'max_mikrotiks',
        'max_customers',
        'phone',
        'address',
    ];

     public $incrementing = false; // non-incrementing primary key
    protected $keyType = 'string'; // primary key bertipe string (UUID)
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
<<<<<<< HEAD

    //automatic genreate uuid for id when cerating a new user
=======
    protected $casts = [
        'email_verified_at' => 'datetime',
        'subscription_start' => 'datetime',
        'subscription_end' => 'datetime',
        'trial_ends_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    protected $dates = [
        'subscription_start',
        'subscription_end',
        'trial_ends_at',
        'deleted_at'
    ];

    // Automatic generate uuid for id when creating a new user
>>>>>>> 74809ab (huh)
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) \Illuminate\Support\Str::uuid();
            }
        });
    }

<<<<<<< HEAD
    protected $casts = [
        'email_verified_at' => 'datetime',
        'subscription_start' => 'datetime',
        'subscription_end' => 'datetime',
        'trial_ends_at' => 'datetime',
    ];

    protected $dates = [
        'subscription_start',
        'subscription_end',
        'trial_ends_at',
    ];

  
=======
    public function packages()
    {
        return $this->hasMany(Package::class);
    }
    public function ipPools ()
    {
        return $this->hasMany(IpPool::class);
    }
    public function customers()
    {
        return $this->hasMany(Customer::class);
    }

    public function bankAccounts()
    {
        return $this->hasMany(BankAccount::class, 'created_by');
    }

    public function activeBankAccounts()
    {
        return $this->bankAccounts()->where('is_active', true);
    }

    public function mikrotiks()
    {
        return $this->hasMany(Mikrotik::class, 'created_by');
    }
>>>>>>> 74809ab (huh)
}
