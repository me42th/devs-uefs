<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_post()
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/posts', [
            'title' => 'New Post',
            'content' => 'This is the content of the post.',
            'user_id' => $user->id,
        ]);

        $response->assertStatus(201)
                 ->assertJsonPath('title', 'New Post');
    }

    public function test_can_get_all_posts()
    {
        Post::factory()->count(3)->create();

        $response = $this->getJson('/api/posts');

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    public function test_can_get_specific_post()
    {
        $post = Post::factory()->create();

        $response = $this->getJson("/api/posts/{$post->id}");

        $response->assertStatus(200)
                 ->assertJsonPath('id', $post->id);
    }

    public function test_can_update_post()
    {
        $post = Post::factory()->create();

        $response = $this->putJson("/api/posts/{$post->id}", [
            'title' => 'Updated Title',
            'content' => 'Updated content of the post.',
        ]);

        $response->assertStatus(200)
                 ->assertJsonPath('title', 'Updated Title');
    }

    public function test_can_delete_post()
    {
        $post = Post::factory()->create();

        $response = $this->deleteJson("/api/posts/{$post->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }
}