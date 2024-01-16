<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EskizToken extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'eskiz_token';

    protected $fillable = [
        'token',
        'expire_date'
    ];
}
