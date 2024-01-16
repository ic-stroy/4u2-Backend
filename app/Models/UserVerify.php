<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserVerify extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'user_verifies';

    protected $fillable = [
        'phone_number',
        'user_id',
        'verify_code',
        'verify_date',
        'status_id',
    ];

    public function user(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
