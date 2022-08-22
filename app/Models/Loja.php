<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loja extends Model
{
    use HasFactory;

    protected $table = 'lojas';
    protected $primaryKey = 'id';

    protected $fillable = [
        'nome',
        'callback_url', 
        'api_integration'
    ];
}
