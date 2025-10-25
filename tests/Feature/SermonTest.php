<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Sermon;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SermonTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test sermons index page can be displayed
     */
    public function test_sermons_index_page_can_be_displayed(): void
    {
        $response = $this->get('/sermons');

        $response->assertStatus(200);
    }

    /**
     * Test sermon show page can be displayed
     */
    public function test_sermon_show_page_can_be_displayed(): void
    {
        $user = User::factory()->create(['user_type' => 'preacher']);

        $sermon = Sermon::create([
            'title' => 'Test Sermon',
            'slug' => 'test-sermon',
            'content' => 'This is a test sermon content with more than 100 characters.',
            'category' => 'faith',
            'author_id' => $user->id,
            'is_published' => true,
        ]);

        $response = $this->get('/sermons/' . $sermon->id);

        $response->assertStatus(200);
        $response->assertSee('Test Sermon');
    }

    /**
     * Test only published sermons are shown on index
     */
    public function test_only_published_sermons_are_shown_on_index(): void
    {
        $user = User::factory()->create(['user_type' => 'preacher']);

        $publishedSermon = Sermon::create([
            'title' => 'Published Sermon',
            'slug' => 'published-sermon',
            'content' => 'Published content',
            'category' => 'faith',
            'author_id' => $user->id,
            'is_published' => true,
        ]);

        $draftSermon = Sermon::create([
            'title' => 'Draft Sermon',
            'slug' => 'draft-sermon',
            'content' => 'Draft content',
            'category' => 'faith',
            'author_id' => $user->id,
            'is_published' => false,
        ]);

        $response = $this->get('/sermons');

        $response->assertSee('Published Sermon');
        $response->assertDontSee('Draft Sermon');
    }

    /**
     * Test authenticated preacher can create sermon
     */
    public function test_authenticated_preacher_can_access_create_sermon_page(): void
    {
        $user = User::factory()->create(['user_type' => 'preacher']);

        $response = $this->actingAs($user)->get('/sermons/create');

        $response->assertStatus(200);
    }

    /**
     * Test guest cannot access create sermon page
     */
    public function test_guest_cannot_access_create_sermon_page(): void
    {
        $response = $this->get('/sermons/create');

        $response->assertRedirect('/login');
    }

    /**
     * Test sermon views count increments
     */
    public function test_sermon_views_count_increments(): void
    {
        $user = User::factory()->create(['user_type' => 'preacher']);

        $sermon = Sermon::create([
            'title' => 'Test Sermon',
            'slug' => 'test-sermon',
            'content' => 'Test content',
            'category' => 'faith',
            'author_id' => $user->id,
            'is_published' => true,
            'views_count' => 0,
        ]);

        $initialViews = $sermon->views_count;

        $this->get('/sermons/' . $sermon->id);

        $sermon->refresh();

        $this->assertGreaterThan($initialViews, $sermon->views_count);
    }
}

