<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Counties extends Model
{
    use HasFactory;

    protected $table = 'counties';

    protected $fillable = [
        'name',
        'type',
        'discount',
        'start_date',
        'end_date',
    ];
}
