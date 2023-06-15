<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResidentServices extends Model
{
    use HasFactory;

    protected $table = 'resident_services';

    protected $fillable = [
        'name',
        'description',
        'category',
        'type',
        'added_by',
        'start_date',
        'end_date',
        'discount'
    ];

}
