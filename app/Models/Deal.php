<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deal extends Model
{
    protected $fillable = [
        'user_id',
        'drive_deal_id',
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
}
