<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function test_store(): void
    {
        $response = $this->post('/api/user', ['name' => 'test user']);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            "result",
            "id"
        ]);
    }

    public function test_index(): void
    {
        $response = $this->get('/api/user');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            "total",
            "pages",
            "users" => [
                '*' => [
                    "id",
                    "name",
                ]
            ]
        ]);
    }

    public function test_show(): void
    {
        $response = $this->get('/api/user/1');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            "user"
        ]);
    }

    public function test_update(): void
    {
        $consulta = DB::QUERY('SELECT *')->from('users')->where('name', '=', 'test user')->first();
        $response = $this->put('/api/user/' . $consulta->id, ['name' => 'update test user']);

        $response->assertStatus(200);
        $response->assertJson([
            "result" => true
        ]);
    }

    public function test_destroy(): void
    {
        $consulta = DB::QUERY('SELECT *')->from('users')->where('name', 'like', '%test user%')->first();
        $response = $this->delete('/api/user/' . $consulta->id);

        $response->assertStatus(200);
        $response->assertJson([
            "result" => true
        ]);
    }
}
