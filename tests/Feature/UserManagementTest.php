<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        foreach (['users.view', 'users.create', 'users.update', 'users.delete'] as $permission) {
            Permission::create(['name' => $permission]);
        }

        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo(['users.view', 'users.create', 'users.update', 'users.delete']);

        Role::create(['name' => 'user']);
    }

    private function admin(): User
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        return $user;
    }

    public function test_users_page_requires_authentication(): void
    {
        $this->get('/users')->assertRedirect(route('login'));
    }

    public function test_users_page_requires_permission(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/users')->assertForbidden();
    }

    public function test_users_page_lists_users(): void
    {
        $admin = $this->admin();
        User::factory()->create(['email' => 'someone@example.com']);

        $response = $this->actingAs($admin)->get('/users');

        $response->assertOk();
        $response->assertInertia(
            fn ($page) => $page
                ->component('Users/Index')
                ->has('users.data', 2)
                ->has('roles', 2)
        );
    }

    public function test_users_can_be_searched(): void
    {
        $admin = $this->admin();
        User::factory()->create(['name' => 'Alice']);
        User::factory()->create(['name' => 'Bob']);

        $response = $this->actingAs($admin)->get('/users?search=Alice');

        $response->assertInertia(
            fn ($page) => $page
                ->has('users.data', 1)
                ->where('users.data.0.name', 'Alice')
        );
    }

    public function test_user_can_be_created_with_role(): void
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)->post('/users', [
            'name' => 'New User',
            'email' => 'new@example.com',
            'password' => 'password-123',
            'password_confirmation' => 'password-123',
            'role' => 'user',
        ]);

        $response->assertRedirect();
        $user = User::where('email', 'new@example.com')->first();
        $this->assertTrue(Hash::check('password-123', $user->password));
        $this->assertTrue($user->hasRole('user'));
    }

    public function test_create_requires_unique_email_and_confirmed_password(): void
    {
        $admin = $this->admin();
        User::factory()->create(['email' => 'taken@example.com']);

        $response = $this->actingAs($admin)->post('/users', [
            'name' => 'Dup',
            'email' => 'taken@example.com',
            'password' => 'password-123',
            'password_confirmation' => 'different',
        ]);

        $response->assertSessionHasErrors(['email', 'password']);
    }

    public function test_user_can_be_updated_without_password(): void
    {
        $admin = $this->admin();
        $user = User::factory()->create();
        $originalHash = $user->password;

        $response = $this->actingAs($admin)->put("/users/{$user->id}", [
            'name' => 'Renamed',
            'email' => $user->email,
            'password' => '',
            'password_confirmation' => '',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => 'Renamed']);
        $this->assertSame($originalHash, $user->fresh()->password);
    }

    public function test_user_role_can_be_changed(): void
    {
        $admin = $this->admin();
        $user = User::factory()->create();
        $user->assignRole('user');

        $response = $this->actingAs($admin)->put("/users/{$user->id}", [
            'name' => $user->name,
            'email' => $user->email,
            'role' => 'admin',
        ]);

        $response->assertRedirect();
        $this->assertTrue($user->fresh()->hasRole('admin'));
    }

    public function test_admin_cannot_remove_own_role(): void
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)->put("/users/{$admin->id}", [
            'name' => $admin->name,
            'email' => $admin->email,
            'role' => 'user',
        ]);

        $response->assertRedirect();
        $this->assertTrue($admin->fresh()->hasRole('admin'));
    }

    public function test_user_can_be_updated_with_new_password(): void
    {
        $admin = $this->admin();
        $user = User::factory()->create();

        $response = $this->actingAs($admin)->put("/users/{$user->id}", [
            'name' => $user->name,
            'email' => $user->email,
            'password' => 'new-password-123',
            'password_confirmation' => 'new-password-123',
        ]);

        $response->assertRedirect();
        $this->assertTrue(Hash::check('new-password-123', $user->fresh()->password));
    }

    public function test_user_can_be_deleted(): void
    {
        $admin = $this->admin();
        $user = User::factory()->create();

        $response = $this->actingAs($admin)->delete("/users/{$user->id}");

        $response->assertRedirect();
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function test_user_cannot_delete_self(): void
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)->delete("/users/{$admin->id}");

        $response->assertSessionHasErrors('user');
        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }
}
