<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test user creation
     */
    public function test_user_can_be_created(): void
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'user_type' => 'member',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'name' => 'Test User',
        ]);
    }

    /**
     * Test user has correct attributes
     */
    public function test_user_has_correct_attributes(): void
    {
        $user = User::factory()->create([
            'user_type' => 'scholar',
            'is_active' => true,
        ]);

        $this->assertEquals('scholar', $user->user_type);
        $this->assertTrue($user->is_active);
    }

    /**
     * Test user password is hashed
     */
    public function test_user_password_is_hashed(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
        ]);

        $this->assertNotEquals('password123', $user->password);
        $this->assertTrue(\Hash::check('password123', $user->password));
    }

    /**
     * Test user can have sermons relationship
     */
    public function test_user_can_have_sermons(): void
    {
        $user = User::factory()->create(['user_type' => 'preacher']);

        $this->assertInstanceOf(
            \Illuminate\Database\Eloquent\Collection::class,
            $user->sermons
        );
    }

    /**
     * Test user can have articles relationship
     */
    public function test_user_can_have_articles(): void
    {
        $user = User::factory()->create(['user_type' => 'scholar']);

        $this->assertInstanceOf(
            \Illuminate\Database\Eloquent\Collection::class,
            $user->articles
        );
    }
}

