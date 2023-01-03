<?php

namespace App\Console\Commands;

use App\Models\Pedido;
use App\Models\Webhook;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log as FacadesLog;
use Spatie\WebhookServer\WebhookCall;

class SendOrdersWebhook extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orderwebhook:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Webhook Dispatched';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        FacadesLog::info('Webhook started');

        $lastWebhook = Webhook::query()->latest()->first()->attributesToArray();
        $lastWebhookTime = $lastWebhook['created_at'];
        $lastOrder = Pedido::query()->latest()->first();
        
        if($lastOrder){

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
        }    
    }

}
