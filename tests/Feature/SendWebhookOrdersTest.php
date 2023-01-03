<?php

namespace Tests\Feature;

use App\Models\Loja;
use App\Models\Pedido;
use App\Models\Webhook;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Spatie\WebhookServer\CallWebhookJob;
use Tests\TestCase;

class SendWebhookOrdersTest extends TestCase
{

    use RefreshDatabase;

    /**
     * 
     * @test
     * @return void
     */
    public function it_behaves_as_expected_when_there_is_only_one_order_to_dispatch()
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
        Bus::fake();
        
        $this->artisan('orderwebhook:send')->assertSuccessful();
        Bus::assertDispatched(CallWebhookJob::class);
        $this->assertDatabaseCount('webhooks', 6);
    }

    /**
     * 
     * @test
     * @return void
     */
    public function it_behaves_as_expected_when_the_most_recent_order_was_created_before_the_last_webhook()
    {
        $orders = Pedido::factory()->count(5)->create([
            'created_at' => '2022-08-14T14:14:56.000000Z'
        ]);
        $webhook = Webhook::factory()->count(5)->create([
            'created_at' => '2022-08-15T14:14:56.000000Z'
        ]);
        Bus::fake();
        
        $this->artisan('orderwebhook:send')->assertSuccessful();
        Bus::assertNotDispatched(CallWebhookJob::class);
        $this->assertDatabaseCount('webhooks', 5);
    }

     /**
     * 
     * @test
     * @return void
     */
    public function it_behaves_as_expected_when_there_is_no_order_to_dispatch()
    {
        $lojaWithoutApiIntegration = Loja::factory()->create([
            'api_integration' => 0
        ]);
        $orders = Pedido::factory()->count(5)->create([
            'created_at' => '2022-08-14T14:14:56.000000Z'
        ]);
        $lastOrderInDatabase = Pedido::factory()->create([
            'loja_id' => $lojaWithoutApiIntegration
        ]);
        $webhook = Webhook::factory()->count(5)->create([
            'created_at' => '2022-08-15T14:14:56.000000Z'
        ]);
        Bus::fake();

        $this->artisan('orderwebhook:send')->assertSuccessful();
        Bus::assertNotDispatched(CallWebhookJob::class);
        $this->assertDatabaseCount('webhooks', 5);
    }

    /**
     * 
     * @test
     * @return void
     */
    public function it_behaves_as_expected_when_there_are_multiple_orders_to_dispatch()
    {
        $lojaWithApiIntegration = Loja::factory()->create([
            'api_integration' => 1
        ]);
        $orders = Pedido::factory()->count(5)->create([
            'created_at' => '2022-08-14T14:14:56.000000Z'
        ]);
        $lastOrderInDatabase = Pedido::factory()->count(5)->create([
            'loja_id' => $lojaWithApiIntegration
        ]);
        $webhook = Webhook::factory()->count(5)->create([
            'created_at' => '2022-08-15T14:14:56.000000Z'
        ]);
        Bus::fake();

        $this->artisan('orderwebhook:send')->assertSuccessful();
        Bus::assertDispatched(CallWebhookJob::class);
        $this->assertDatabaseCount('webhooks', 10);
    }
}
