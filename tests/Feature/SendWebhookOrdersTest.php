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

        
        $lastWebhook = Webhook::query()->latest()->first()->attributesToArray();
        $lastWebhookTime = $lastWebhook['created_at'];
        $lastOrder = Pedido::query()->where('created_at', '>', $lastWebhookTime)->latest()->first()->attributesToArray();
        
        if($lastOrder){
            Bus::fake();

            $newOrders = Pedido::query()
            ->select()
            ->join('lojas', 'pedidos.loja_id', '=', 'lojas.id')
            ->where([
                ['lojas.api_integration', '=', 1],
                ['pedidos.created_at', '>', $lastWebhookTime],
                ['lojas.callback_url', '!=', null]
            ])
            ->get();

            if(sizeof($newOrders) > 0){
                foreach($newOrders as $order){
                    $data = $order->attributesToArray();
                    WebhookCall::create()
                    ->url($data['callback_url'])
                    ->payload([
                        'pedido_id' => $data['id'],
                        'loja_id' => $data['loja_id'],
                        'produto_id' => $data['produto_id'],
                        'usuario_id' => $data['usuario_id'],
                        'quantidade' => $data['quantidade'],
                        'data' => $data['created_at']
                    ])
                    ->useSecret('teste')
                    ->dispatch();
                }
            }

            Bus::assertDispatched(CallWebhookJob::class);
        }

    }
}
