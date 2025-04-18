<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;
    private User $user;
    public function setUp(): void
    {
        parent::setUp();
        $this->user = new User;
    }
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/api/users');

        $response->assertStatus(200);
    }

    public function test_create_user(): void
    {
        $userRequestBody = [
            'name' => 'Felipe Eduardo Monari',
            'email' => 'felipe@gmail.com',
            'password' => 'felipe',
        ];

        $userResponse = $this->post('/api/users', $userRequestBody);
        $userResponseBody = $userResponse->json();
        $userResponse->assertJson([
            'id' => $userResponseBody['id'],
            'name' => $userRequestBody['name'],
            'email' => $userRequestBody['email'],
            'updated_at' => $userResponseBody['updated_at'],
            'created_at' => $userResponseBody['created_at'],
        ]);
        $userResponse->assertJsonMissing(['password']);
        $this->assertDatabaseCount($this->user->getTable(), 1);
    }
}
