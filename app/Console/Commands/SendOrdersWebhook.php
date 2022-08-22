<?php

namespace App\Console\Commands;

use App\Models\Pedido;
use App\Models\Webhook;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log as FacadesLog;

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

        
    }

}
