<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Loja>
 */
class LojaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'nome' => 'teste loja poc',
            'callback_url' => 'http://localhost:8080',
            'api_integration' => random_int(0,1),
            'created_at' => now(),
            'updated_at' => now()
        ];
    }
}
