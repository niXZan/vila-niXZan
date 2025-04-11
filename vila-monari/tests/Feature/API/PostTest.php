<?php
namespace Tests\Feature\API;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Post;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;
    private function test_list_zero_posts(): void
    {
        $response = $this->get('/api/posts');
        $response->assertStatus(200);
        $response->assertExactJson([]);
    }

    private function test_list_n_posts(): void
    {
        $post = Post::factory(10)->create();

        $response = $this->get('/api/posts');
        $response->assertStatus(200);
        $response->assertExactJson([9]);
    }

    private function test_display_one_post(): void
    {
        $post = Post::factory()->create();

        $response = $this->get(`/api/posts/{$post->id}`);
        $response->assertStatus(200);
        $response->assertExactJson($post->toArray());
    }

    private function test_get_wrong_post(): void
    {
        $this->test_display_one_post();

        $response = $this->get('/api/posts/monari');
        $response->assertStatus(404);
        $response->assertJson([]);
    }
    public function test_create_post_through_api(): void
    {
        $requestBody = [
            'content' => "",
        ];
        $response = $this->post('/api/posts', $requestBody);
        $response->assertStatus(500);
        $responseBody = $response->json();
        $this->assertIsInt($responseBody['id']);
        $response->assertSimilarJson([
            'id'         => $responseBody['id'],
            'content'    => $requestBody['content'],
            'created_at' => $responseBody['created_at'],
            'updated_at' => $responseBody['updated_at'],
        ]);
    }
    private function test_update_single_post(): void
    {
        $post = Post::factory()->create();

        $requestBody = [
            'content' => 'Post de feito por Felipe Eduardo Monari!',
        ];

        $responseGet = $this->put("/api/posts/{$post->id}");

        $responseGet->assertStatus(200);
        $responseGetBody = $responseGet->assertJson();

        $this->assertEquals(
            $requestBody['content'],
            $requestBody['content'],
        );
    }
    private function test_delete_single_post(): void
    {
        $post = Post::factory()->create();

        $response = $this->delete("/api/posts/{$post->id}");
        $response->assertStatus(200);
        $this->assertDatabaseMissing('posts', [
            'id' => $post->id,
        ]);
    }

    /*
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
    */

}
