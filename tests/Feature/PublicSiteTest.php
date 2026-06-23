<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
use App\Models\Post;
use App\Models\Project;
use Database\Seeders\ContentSeeder;
use Database\Seeders\RoleAndAdminSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicSiteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([RoleAndAdminSeeder::class, ContentSeeder::class]);
    }

    public function test_public_pages_render(): void
    {
        foreach (['/', '/about', '/services', '/portfolio', '/blog', '/contact', '/sitemap.xml'] as $url) {
            $this->get($url)->assertStatus(200);
        }
    }

    public function test_detail_pages_render(): void
    {
        $this->get('/portfolio/'.Project::first()->slug)->assertStatus(200);
        $this->get('/blog/'.Post::first()->slug)->assertStatus(200);
    }

    public function test_language_switch_changes_locale(): void
    {
        $this->get('/lang/en');
        $this->assertEquals('en', session('locale'));

        $this->get('/lang/id');
        $this->assertEquals('id', session('locale'));
    }

    public function test_contact_form_stores_message(): void
    {
        $response = $this->post('/contact', [
            'name' => 'Budi',
            'email' => 'budi@example.com',
            'subject' => 'Halo',
            'message' => 'Pesan uji.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('contact_messages', [
            'email' => 'budi@example.com',
            'is_read' => false,
        ]);
    }

    public function test_contact_honeypot_blocks_spam(): void
    {
        $this->post('/contact', [
            'name' => 'Spam',
            'email' => 'spam@example.com',
            'message' => 'spam',
            'website' => 'http://spam.com', // honeypot terisi
        ]);

        $this->assertDatabaseMissing('contact_messages', ['email' => 'spam@example.com']);
    }
}
