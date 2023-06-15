<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MoveOut extends Model
{
    use HasFactory;

    protected $table = 'move_out';

    protected $fillable = [
        'user_id',
        'user_first_name',
        'user_last_name',
        'updated_by',
        'date',
    ];
}
