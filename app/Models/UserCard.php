<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserCard extends Model
{
    use HasFactory, SoftDeletes;

    public $fillable = [
      'id',
      'name',
      'card_number',
      'validity_period',
      'user_id',
    ];

    protected $table = 'user_cards';
}
