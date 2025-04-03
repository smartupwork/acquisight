<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DealRequest extends Model
{

    protected $table = 'deal_requests';

    protected $fillable = ['deal_id', 'user_id', 'broker_id', 'status'];

    public function deal()
    {
        return $this->belongsTo(Deal::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function broker()
    {
        return $this->belongsTo(User::class, 'broker_id');
    }
}
