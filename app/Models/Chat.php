<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;
    
    protected $table = 'chats';

    protected $fillable = [
        'admin_id',
        'admin_email',
        'admin_name',
        'resident_id',
        'resident_email',
        'resident_name',
        'content',
        'sender_is_admin',
    ];
}
