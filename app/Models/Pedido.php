<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    protected $table = 'pedidos';
    protected $primaryKey = 'id';

    protected $fillable = [
        'loja_id',
        'usuario_id',
        'produto_id',
        'quantidade'
    ];
}
