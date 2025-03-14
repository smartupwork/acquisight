<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DealMeta extends Model
{
    protected $table = 'deals_meta';

    protected $fillable = [
        'deal_id',
        'asking_price',
        'gross_revenue',
        'cash_flow',
        'ebitda',
        'inventory',
        'ffe',
        'business_desc',
        'location',
        'no_employee',
        'real_estate',
        'rent',
        'lease_expiration',
        'facilities',
        'market_outlook',
        'selling_reason',
        'train_support'
    ];
}
