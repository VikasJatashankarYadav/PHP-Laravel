<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceUsed extends Model
{
    use HasFactory;

    protected $table = 'service_used';

    protected $fillable = [
        'user_id',
        'resident_service_id',
        'resident_service_name',
        'value',
    ];
}
