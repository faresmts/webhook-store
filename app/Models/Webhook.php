<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Webhook extends Model
{
    use HasFactory;

    protected $table = 'webhooks';
    protected $primaryKey = 'id';

    protected $fillable = [
        'uuid',
        'signature',
        'callback_url',
    ];
}
