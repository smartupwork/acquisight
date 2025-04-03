<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deal extends Model
{
    protected $fillable = [
        'user_id',
        'gcs_deal_id',
        'name',
        'description',
        'status',
        'deal_image'
    ];

    public function dealCreatedBy()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function folders()
    {
        return $this->hasMany(DealFolder::class);
    }

    public function files()
    {
        return $this->hasMany(DealFile::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function dealFolders()
    {
        return $this->hasMany(DealFolder::class);
    }


    public function dealFiles()
    {
        return $this->hasMany(DealFile::class);
    }

    public function invitations()
    {
        return $this->hasMany(DealInvitation::class, 'deal_id');
    }

    public function requests()
    {
        return $this->hasMany(DealRequest::class);
    }

    public function broker()
    {
        return $this->hasOneThrough(User::class, DealInvitation::class, 'deal_id', 'email', 'id', 'email');
    }
}
