<?php

namespace Tests\Feature;

use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TagTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_tag()
    {
        $response = $this->postJson('/api/tags', [
            'name' => 'Technology',
        ]);

        $response->assertStatus(201)
                 ->assertJsonPath('name', 'Technology');
    }

    public function test_can_get_all_tags()
    {
        Tag::factory()->count(3)->create();

        $response = $this->getJson('/api/tags');

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    public function test_can_get_specific_tag()
    {
        $tag = Tag::factory()->create();

        $response = $this->getJson("/api/tags/{$tag->id}");

        $response->assertStatus(200)
                 ->assertJsonPath('id', $tag->id);
    }

    public function test_can_update_tag()
    {
        $tag = Tag::factory()->create();

        $response = $this->putJson("/api/tags/{$tag->id}", [
            'name' => 'Science',
        ]);

        $response->assertStatus(200)
                 ->assertJsonPath('name', 'Science');
    }

    public function test_can_delete_tag()
    {
        $tag = Tag::factory()->create();

        $response = $this->deleteJson("/api/tags/{$tag->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('tags', ['id' => $tag->id]);
    }
}