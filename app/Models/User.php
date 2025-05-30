<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'verification_token',
        'email_verified_at',
        'password',
        'roles_id',
        'ip_address',
        'status',
        'phone',
        'dob',
        'address'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
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

    public function role()
    {
        return $this->belongsTo(Role::class, 'roles_id');
    }


    public function deals()
    {
        return $this->hasMany(Deal::class, 'user_id');
    }

    public function dealFiles()
    {
        return $this->hasMany(DealFile::class);
    }

    // Relationship with File View Logs (user who viewed files)
    public function fileViewLogs()
    {
        return $this->hasMany(FileViewLog::class);
    }

    public function dealInvitation()
    {
        return $this->hasOne(DealInvitation::class, 'email', 'email');
    }

    public function dealRequests()
    {
        return $this->hasMany(DealRequest::class, 'user_id');
    }

    
    public function brokerRequests()
    {
        return $this->hasMany(DealRequest::class, 'broker_id');
    }
}
