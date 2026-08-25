<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_page_requires_authentication(): void
    {
        $this->get('/profile')->assertRedirect(route('login'));
    }

    public function test_user_can_update_name_and_email(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->put('/profile', [
            'name' => 'New Name',
            'email' => 'new@example.com',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'New Name',
            'email' => 'new@example.com',
        ]);
    }

    public function test_email_must_be_unique(): void
    {
        $other = User::factory()->create(['email' => 'taken@example.com']);
        $user = User::factory()->create();

        $response = $this->actingAs($user)->put('/profile', [
            'name' => $user->name,
            'email' => 'taken@example.com',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_user_can_change_password(): void
    {
        $user = User::factory()->create(['password' => Hash::make('old-password')]);

        $response = $this->actingAs($user)->put('/profile/password', [
            'current_password' => 'old-password',
            'password' => 'new-password-123',
            'password_confirmation' => 'new-password-123',
        ]);

        $response->assertRedirect();
        $this->assertTrue(Hash::check('new-password-123', $user->fresh()->password));
    }

    public function test_current_password_must_be_correct(): void
    {
        $user = User::factory()->create(['password' => Hash::make('old-password')]);

        $response = $this->actingAs($user)->put('/profile/password', [
            'current_password' => 'wrong-password',
            'password' => 'new-password-123',
            'password_confirmation' => 'new-password-123',
        ]);

        $response->assertSessionHasErrors('current_password');
    }
}
