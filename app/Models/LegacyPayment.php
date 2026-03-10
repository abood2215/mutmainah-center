<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LegacyPayment extends Model
{
    protected $table = 'kpayments';
    protected $guarded = [];
    public $timestamps = false;
}
