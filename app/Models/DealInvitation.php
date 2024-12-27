<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DealInvitation extends Model
{
    protected $table = 'deal_invitations';

    protected $fillable = [
        'deal_id',
        'email',
        'token',
        'accepted'
    ];
}
