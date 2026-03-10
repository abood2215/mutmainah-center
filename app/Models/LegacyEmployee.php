<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LegacyEmployee extends Model
{
    protected $table = 'employees';
    protected $guarded = [];
    public $timestamps = false;
}
