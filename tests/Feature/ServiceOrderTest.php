<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ServiceOrderTest extends TestCase
{
    public function test_store(): void
    {
        $response = $this->post('/api/service_order', [
            "vehiclePlate" => "1234567",
            "entryDateTime" => "2023-05-23 00:00:00",
            "userId" => 1
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            "result",
            "id"
        ]);
    }

    public function test_index(): void
    {
        $response = $this->get('/api/service_order');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            "total",
            "pages",
            "service_orders" => [
                '*' => [
                    "id",
                    "vehiclePlate",
                    "entryDateTime",
                    "exitDateTime",
                    "priceType",
                    "price",
                    "userId",
                    "user" => [
                        "id",
                        "name",
                    ]
                ]
            ]
        ]);
    }

    public function test_show(): void
    {
        $response = $this->get('/api/service_order/1');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            "service_order" => [
                "id",
                "vehiclePlate",
                "entryDateTime",
                "exitDateTime",
                "priceType",
                "price",
                "userId",
                "user" => [
                    "id",
                    "name",
                ]
            ]
        ]);
    }

    public function test_update(): void
    {
        $consulta = DB::QUERY('SELECT *')->from('service_orders')->where('vehiclePlate', '=', '1234567')->first();
        $consulta->vehiclePlate = '123456';

        $response = $this->put('/api/service_order/' . $consulta->id, (array) $consulta);

        $response->assertStatus(200);
        $response->assertJson([
            "result" => true
        ]);
    }

    public function test_destroy(): void
    {
        $consulta = DB::QUERY('SELECT *')->from('service_orders')->where('vehiclePlate', 'like', '%123456%')->first();
        $response = $this->delete('/api/service_order/' . $consulta->id);

        $response->assertStatus(200);
        $response->assertJson([
            "result" => true
        ]);
    }
}
