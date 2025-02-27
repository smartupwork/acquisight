<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DealFolder extends Model
{

    protected $table = 'deal_folders';

    protected $fillable = [
        'deal_id',
        'folder_name',
        'gcs_folder_id'
    ];

    public function deal()
    {
        return $this->belongsTo(Deal::class);
    }

    public function files()
    {
        return $this->hasMany(DealFile::class, 'folder_id');
    }

    public function dealFiles()
    {
        return $this->hasMany(DealFile::class);
    }
}
