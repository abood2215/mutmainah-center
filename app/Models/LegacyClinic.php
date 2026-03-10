<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LegacyClinic extends Model
{
    protected $table = 'clinic';
    protected $guarded = [];
    public $timestamps = false;
}
