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
}
