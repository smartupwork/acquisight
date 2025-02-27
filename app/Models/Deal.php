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
        'status'
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

    // Relationship with Deal Folders
    public function dealFolders()
    {
        return $this->hasMany(DealFolder::class);
    }

    // Relationship with Deal Files
    public function dealFiles()
    {
        return $this->hasMany(DealFile::class);
    }
}
