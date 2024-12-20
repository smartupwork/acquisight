<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DealAccessLog extends Model
{
    protected $table = 'deal_access_logs';

    protected $fillable = [
        'file_id',
        'user_id',
        'viewed_at'
    ];


    public function file()
    {
        return $this->belongsTo(DealFile::class);
    }

    public function fileAccessedBy()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
