<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DealFile extends Model
{
    protected $table = 'deal_files';

    protected $fillable = [
        'deal_id',
        'folder_id',
        'user_id',
        'file_path',
        'file_name'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function deal()
    {
        return $this->belongsTo(Deal::class);
    }

    public function folder()
    {
        return $this->belongsTo(DealFolder::class);
    }

    public function uploadedBy()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function fileViews()
    {
        return $this->hasMany(FileViewLog::class);
    }

    public function dealFolder()
    {
        return $this->belongsTo(DealFolder::class, 'folder_id');
    }

    public function fileViewLogs()
    {
        return $this->hasMany(FileViewLog::class, 'file_id', 'id');
    }
}
