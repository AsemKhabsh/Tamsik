<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Sermon;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SermonTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test sermon can be created
     */
    public function test_sermon_can_be_created(): void
    {
        $user = User::factory()->create(['user_type' => 'preacher']);

        $sermon = Sermon::create([
            'title' => 'Test Sermon',
            'slug' => 'test-sermon',
            'content' => 'This is a test sermon content with more than 100 characters to pass validation rules.',
            'category' => 'faith',
            'author_id' => $user->id,
            'is_published' => true,
        ]);

        $this->assertDatabaseHas('sermons', [
            'title' => 'Test Sermon',
            'slug' => 'test-sermon',
        ]);
    }

    /**
     * Test sermon belongs to author
     */
    public function test_sermon_belongs_to_author(): void
    {
        $user = User::factory()->create(['user_type' => 'preacher']);

        $sermon = Sermon::create([
            'title' => 'Test Sermon',
            'slug' => 'test-sermon',
            'content' => 'This is a test sermon content with more than 100 characters to pass validation.',
            'category' => 'faith',
            'author_id' => $user->id,
        ]);

        $this->assertInstanceOf(User::class, $sermon->author);
        $this->assertEquals($user->id, $sermon->author->id);
    }

    /**
     * Test sermon has correct casts
     */
    public function test_sermon_has_correct_casts(): void
    {
        $user = User::factory()->create(['user_type' => 'preacher']);

        $sermon = Sermon::create([
            'title' => 'Test Sermon',
            'slug' => 'test-sermon',
            'content' => 'Test content',
            'category' => 'faith',
            'author_id' => $user->id,
            'is_published' => true,
            'tags' => ['tag1', 'tag2'],
        ]);

        $this->assertTrue(is_bool($sermon->is_published));
        $this->assertTrue(is_array($sermon->tags));
    }

    /**
     * Test sermon scope published
     */
    public function test_sermon_scope_published(): void
    {
        $user = User::factory()->create(['user_type' => 'preacher']);

        Sermon::create([
            'title' => 'Published Sermon',
            'slug' => 'published-sermon',
            'content' => 'Content',
            'category' => 'faith',
            'author_id' => $user->id,
            'is_published' => true,
        ]);

        Sermon::create([
            'title' => 'Draft Sermon',
            'slug' => 'draft-sermon',
            'content' => 'Content',
            'category' => 'faith',
            'author_id' => $user->id,
            'is_published' => false,
        ]);

        $publishedSermons = Sermon::published()->get();

        $this->assertEquals(1, $publishedSermons->count());
        $this->assertEquals('Published Sermon', $publishedSermons->first()->title);
    }
}

