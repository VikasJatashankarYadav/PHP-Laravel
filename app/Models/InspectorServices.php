<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InspectorServices extends Model
{
    use HasFactory;

    protected $table = 'inspector_services';

    protected $fillable = [
        'user_id',
        'resident_service_id',
        'added_by',
    ];
}
