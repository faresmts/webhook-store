<?php

namespace Tests\Feature;

use App\Models\Loja;
use App\Models\Pedido;
use App\Models\Webhook;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Spatie\WebhookServer\CallWebhookJob;
use Spatie\WebhookServer\WebhookCall;
use Tests\TestCase;

class SendWebhookOrdersTest extends TestCase
{

    use RefreshDatabase;
    /**
     * 
     * @test
     * @return void
     */
    public function debuging()
    {
        $lojaWithApiIntegration = Loja::factory()->create([
            'api_integration' => 1
        ]);
        $orders = Pedido::factory()->count(5)->create([
            'created_at' => '2022-08-14T14:14:56.000000Z'
        ]);
        $lastOrderInDatabase = Pedido::factory()->create([
            'loja_id' => $lojaWithApiIntegration
        ]);
        $webhook = Webhook::factory()->count(5)->create([
            'created_at' => '2022-08-15T14:14:56.000000Z'
        ]);


    }
}
