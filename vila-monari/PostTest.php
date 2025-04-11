<?php
namespace Tests\Feature\API;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;
    public function test_list_zero_posts(): void
    {
        $response = $this->get('/api/posts');
        $response->assertStatus(200);
        $response->assertExactJson([]);
    }

    public function test_create_post(): void
    {
        $requestBody = [
            'content' => 'Post de feito por Felipe Eduardo Monari!',
        ];

        $response = $this->post('/api/posts', $requestBody);

        $response->assertStatus(201);

        $responseBody = $response->json();
        $this->assertIsInt($responseBody['id']);
        $this->assertLessThanOrEqual(32, strlen($responseBody['id']));
        $this->assertLessThan(255, strlen($responseBody['image']));

        $response->assertSimilarJson([
            'id'         => $responseBody['id'],
            'content'    => $requestBody['content'],
            'created_at' => $responseBody['created_at'],
            'updated_at' => $responseBody['updated_at'],
        ]);

        $response = $this->get("/api/posts/{$responseBody['id']}");
        $response->assertStatus(200);
        $response->assertExactJson([
            'id'         => $responseBody['id'],
            'username'   => 'anon',
            'content'    => $requestBody['content'],
            'image'      => null,
            'created_at' => $responseBody['created_at'],
            'updated_at' => $responseBody['updated_at'],
        ]);
    }

    public function test_update_single_post(): void
    {
        $post = Post::factory()->create();

        $post = [
            'content' => 'Post de feito por Felipe Eduardo Monari!',
        ];

        $response = $this->put("/api/posts/{$post->id}", $requestBody);

        $response->assertStatus(200);
        $response->assertExactJson([
            'id'         => $post->id,
            'username'   => 'anon',
            'content'    => $requestBody['content'],
            'image'      => null,
            'created_at' => $post->created_at,
            'updated_at' => now(),
        ]);
    }
}
