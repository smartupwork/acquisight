<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FileViewLog extends Model
{
    protected $table = 'file_view_logs';

    protected $fillable = ['user_id', 'file_id', 'file_name', 'ip_address', 'user_agent', 'viewed_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function dealFile()
    {
        return $this->belongsTo(DealFile::class, 'file_id', 'id');
    }
}
