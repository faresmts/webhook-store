<?php

namespace Database\Factories;

use App\Models\Loja;
use App\Models\Produto;
use App\Models\Usuario;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pedido>
 */
class PedidoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'usuario_id' => Usuario::factory()->create(),
            'produto_id' => Produto::factory()->create(),
            'loja_id' => Loja::factory()->create(),
            'quantidade' => random_int(1, 100),
            'created_at' => fake()->name(), 
            'updated_at' => now()
        ];
    }
}
