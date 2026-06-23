<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\ContentSeeder;
use Database\Seeders\RoleAndAdminSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BackofficeRenderTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([RoleAndAdminSeeder::class, ContentSeeder::class]);
    }

    private function admin(): User
    {
        return User::where('email', 'admin@kurniafedora.com')->first();
    }

    public function test_login_page_is_accessible(): void
    {
        $this->get('/backoffice/login')->assertStatus(200);
    }

    public function test_resource_index_pages_render(): void
    {
        $admin = $this->admin();

        $pages = [
            '/backoffice',
            '/backoffice/services',
            '/backoffice/projects',
            '/backoffice/project-categories',
            '/backoffice/posts',
            '/backoffice/post-categories',
            '/backoffice/pages',
            '/backoffice/team-members',
            '/backoffice/users',
            '/backoffice/contact-messages',
            '/backoffice/manage-settings',
        ];

        foreach ($pages as $url) {
            $this->actingAs($admin)->get($url)->assertStatus(200);
        }
    }

    public function test_resource_create_pages_render(): void
    {
        $admin = $this->admin();

        $pages = [
            '/backoffice/services/create',
            '/backoffice/projects/create',
            '/backoffice/project-categories/create',
            '/backoffice/posts/create',
            '/backoffice/post-categories/create',
            '/backoffice/pages/create',
            '/backoffice/team-members/create',
            '/backoffice/users/create',
        ];

        foreach ($pages as $url) {
            $this->actingAs($admin)->get($url)->assertStatus(200);
        }
    }

    public function test_editor_cannot_access_users_and_settings(): void
    {
        $editor = User::factory()->create();
        $editor->assignRole('Editor');

        // Editor boleh akses konten
        $this->actingAs($editor)->get('/backoffice/services')->assertStatus(200);

        // Editor TIDAK boleh akses users & settings (Admin-only)
        $this->actingAs($editor)->get('/backoffice/users')->assertStatus(403);
        $this->actingAs($editor)->get('/backoffice/manage-settings')->assertStatus(403);
    }
}
