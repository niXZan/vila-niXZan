<?php

namespace Tests\Feature\API;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostTest extends TestCase
{
    /**
     * A basic feature test example.
     */

     //testa a rota que mostra todos os posts
    public function test_list_all_posts(): void
    {
        $response = $this->get('/api/posts');
        $response->assertStatus(200);
    }
    //testa a rota que mostra um post específico
    public function test_show_a_single_post(): void
    {
        $response = $this->get('/api/posts/40');
        $response->assertStatus(200);
    }
    public function test_post_must_not_exists(): void
    {
        $response1 = $this->get('/api/posts/0');
        $response1->assertStatus(404);


        $response2 = $this->get('/api/posts/a');
        $response2->assertStatus(404);
    }
}
