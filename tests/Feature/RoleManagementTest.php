<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RoleManagementTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        $role = Role::create(['name' => 'admin']);
        $role->givePermissionTo(Permission::create(['name' => 'roles.manage']));

        $user = User::factory()->create();
        $user->assignRole('admin');

        return $user;
    }

    public function test_roles_page_requires_authentication(): void
    {
        $this->get('/roles')->assertRedirect(route('login'));
    }

    public function test_roles_page_requires_permission(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/roles')->assertForbidden();
    }

    public function test_roles_page_lists_roles_and_permissions(): void
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)->get('/roles');

        $response->assertOk();
        $response->assertInertia(
            fn ($page) => $page
                ->component('Roles/Index')
                ->has('roles', 1)
                ->has('permissions', 1)
        );
    }

    public function test_role_can_be_created_with_permissions(): void
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)->post('/roles', [
            'name' => 'editor',
            'permissions' => ['roles.manage'],
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('roles', ['name' => 'editor']);
        $this->assertTrue(Role::where('name', 'editor')->first()->hasPermissionTo('roles.manage'));
    }

    public function test_role_can_be_updated(): void
    {
        $admin = $this->admin();
        $role = Role::create(['name' => 'editor']);

        $response = $this->actingAs($admin)->put("/roles/{$role->id}", [
            'name' => 'reviewer',
            'permissions' => [],
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('roles', ['id' => $role->id, 'name' => 'reviewer']);
    }

    public function test_admin_role_cannot_be_renamed_or_deleted(): void
    {
        $admin = $this->admin();
        $role = Role::where('name', 'admin')->first();

        $this->actingAs($admin)->put("/roles/{$role->id}", [
            'name' => 'superadmin',
            'permissions' => [],
        ])->assertSessionHasErrors('name');

        $this->actingAs($admin)->delete("/roles/{$role->id}")->assertSessionHasErrors('role');

        $this->assertDatabaseHas('roles', ['id' => $role->id, 'name' => 'admin']);
    }

    public function test_role_can_be_deleted(): void
    {
        $admin = $this->admin();
        $role = Role::create(['name' => 'editor']);

        $response = $this->actingAs($admin)->delete("/roles/{$role->id}");

        $response->assertRedirect();
        $this->assertDatabaseMissing('roles', ['id' => $role->id]);
    }
}
