<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\Project;
use App\Models\ProjectCategory;
use App\Models\Service;
use App\Models\Setting;
use App\Models\TeamMember;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedSettings();
        $this->seedServices();
        $this->seedPortfolio();
        $this->seedBlog();
        $this->seedPages();
        $this->seedTeam();
    }

    private function seedSettings(): void
    {
        $defaults = [
            'site_name' => 'Kurnia Fedora',
            'site_tagline_id' => 'Solusi Perangkat Lunak & Animasi',
            'site_tagline_en' => 'Software & Animation Solutions',
            'contact_email' => 'hello@kurniafedora.com',
            'contact_phone' => '+62 274 000 0000',
            'whatsapp' => '6281200000000',
            'address' => 'Yogyakarta, Indonesia',
            'animation_studio_url' => 'https://studio.kurniafedora.com',
            'social_instagram' => 'https://instagram.com/kurniafedora',
            'social_linkedin' => 'https://linkedin.com/company/kurniafedora',
            'hero_title_id' => 'Membangun Perangkat Lunak & Animasi Berkualitas',
            'hero_title_en' => 'Crafting Quality Software & Animation',
            'hero_subtitle_id' => 'Kami membantu bisnis Anda tumbuh melalui teknologi dan kreativitas visual.',
            'hero_subtitle_en' => 'We help your business grow through technology and visual creativity.',
        ];

        foreach ($defaults as $key => $value) {
            Setting::firstOrCreate(['key' => $key], ['value' => $value]);
        }
    }

    private function seedServices(): void
    {
        Service::firstOrCreate(
            ['type' => 'software'],
            [
                'title' => [
                    'id' => 'Pengembangan Perangkat Lunak',
                    'en' => 'Software Development',
                ],
                'description' => [
                    'id' => 'Aplikasi web, mobile, dan sistem kustom yang dirancang sesuai kebutuhan bisnis Anda.',
                    'en' => 'Web, mobile, and custom systems tailored to your business needs.',
                ],
                'icon' => 'heroicon-o-code-bracket',
                'order' => 1,
                'is_active' => true,
            ]
        );

        Service::firstOrCreate(
            ['type' => 'animation'],
            [
                'title' => [
                    'id' => 'Studio Animasi',
                    'en' => 'Animation Studio',
                ],
                'description' => [
                    'id' => 'Produksi animasi 2D/3D untuk kebutuhan promosi, edukasi, dan hiburan.',
                    'en' => '2D/3D animation production for promotion, education, and entertainment.',
                ],
                'icon' => 'heroicon-o-film',
                'external_url' => 'https://studio.kurniafedora.com',
                'order' => 2,
                'is_active' => true,
            ]
        );
    }

    private function seedPortfolio(): void
    {
        $web = ProjectCategory::firstOrCreate(
            ['slug' => 'web'],
            ['name' => ['id' => 'Aplikasi Web', 'en' => 'Web Application']]
        );
        $mobile = ProjectCategory::firstOrCreate(
            ['slug' => 'mobile'],
            ['name' => ['id' => 'Aplikasi Mobile', 'en' => 'Mobile Application']]
        );

        $projects = [
            [
                'cat' => $web->id,
                'title' => ['id' => 'Sistem Informasi Sekolah', 'en' => 'School Information System'],
                'client' => 'Yayasan Pendidikan',
                'year' => 2025,
                'desc' => [
                    'id' => 'Platform manajemen akademik terintegrasi untuk sekolah.',
                    'en' => 'Integrated academic management platform for schools.',
                ],
                'tech' => ['Laravel', 'MySQL', 'Tailwind CSS'],
                'featured' => true,
            ],
            [
                'cat' => $mobile->id,
                'title' => ['id' => 'Aplikasi Kasir UMKM', 'en' => 'SME Point of Sale App'],
                'client' => 'Retail Nusantara',
                'year' => 2024,
                'desc' => [
                    'id' => 'Aplikasi kasir mobile dengan laporan penjualan real-time.',
                    'en' => 'Mobile POS app with real-time sales reports.',
                ],
                'tech' => ['Flutter', 'Firebase'],
                'featured' => true,
            ],
            [
                'cat' => $web->id,
                'title' => ['id' => 'Portal Berita', 'en' => 'News Portal'],
                'client' => 'Media Lokal',
                'year' => 2024,
                'desc' => [
                    'id' => 'Portal berita dengan manajemen konten dan SEO.',
                    'en' => 'News portal with content management and SEO.',
                ],
                'tech' => ['Laravel', 'Filament'],
                'featured' => false,
            ],
        ];

        foreach ($projects as $p) {
            Project::firstOrCreate(
                ['slug' => Str::slug($p['title']['en'])],
                [
                    'title' => $p['title'],
                    'client' => $p['client'],
                    'year' => $p['year'],
                    'project_category_id' => $p['cat'],
                    'description' => $p['desc'],
                    'tech_stack' => $p['tech'],
                    'is_featured' => $p['featured'],
                ]
            );
        }
    }

    private function seedBlog(): void
    {
        $cat = PostCategory::firstOrCreate(
            ['slug' => 'teknologi'],
            ['name' => ['id' => 'Teknologi', 'en' => 'Technology']]
        );

        $author = User::where('email', 'admin@kurniafedora.com')->first();

        $posts = [
            [
                'title' => ['id' => 'Memilih Tech Stack yang Tepat', 'en' => 'Choosing the Right Tech Stack'],
                'excerpt' => [
                    'id' => 'Panduan singkat memilih teknologi untuk proyek Anda.',
                    'en' => 'A short guide to choosing technology for your project.',
                ],
                'body' => [
                    'id' => '<p>Memilih tech stack yang tepat menentukan keberhasilan jangka panjang sebuah proyek perangkat lunak.</p>',
                    'en' => '<p>Choosing the right tech stack determines the long-term success of a software project.</p>',
                ],
            ],
            [
                'title' => ['id' => 'Peran Animasi dalam Branding', 'en' => 'The Role of Animation in Branding'],
                'excerpt' => [
                    'id' => 'Bagaimana animasi memperkuat identitas merek.',
                    'en' => 'How animation strengthens brand identity.',
                ],
                'body' => [
                    'id' => '<p>Animasi membantu menyampaikan pesan merek secara menarik dan mudah diingat.</p>',
                    'en' => '<p>Animation helps convey brand messages in an engaging and memorable way.</p>',
                ],
            ],
        ];

        foreach ($posts as $p) {
            Post::firstOrCreate(
                ['slug' => Str::slug($p['title']['en'])],
                [
                    'title' => $p['title'],
                    'excerpt' => $p['excerpt'],
                    'body' => $p['body'],
                    'author_id' => $author?->id,
                    'post_category_id' => $cat->id,
                    'status' => 'published',
                    'published_at' => now(),
                ]
            );
        }
    }

    private function seedPages(): void
    {
        Page::firstOrCreate(
            ['key' => 'about'],
            [
                'title' => ['id' => 'Tentang Kami', 'en' => 'About Us'],
                'content' => [
                    'id' => '<p>Kurnia Fedora adalah perusahaan teknologi informasi dan animasi yang berkomitmen menghadirkan solusi digital berkualitas.</p><p><strong>Visi:</strong> Menjadi mitra teknologi dan kreatif terpercaya.</p><p><strong>Misi:</strong> Memberikan layanan perangkat lunak dan animasi terbaik bagi klien.</p>',
                    'en' => '<p>Kurnia Fedora is an information technology and animation company committed to delivering quality digital solutions.</p><p><strong>Vision:</strong> To be a trusted technology and creative partner.</p><p><strong>Mission:</strong> To provide the best software and animation services for clients.</p>',
                ],
            ]
        );
    }

    private function seedTeam(): void
    {
        $members = [
            ['name' => 'Budi Santoso', 'position' => 'Founder & CEO', 'order' => 1],
            ['name' => 'Siti Rahma', 'position' => 'Lead Developer', 'order' => 2],
            ['name' => 'Andi Wijaya', 'position' => 'Animation Director', 'order' => 3],
        ];

        foreach ($members as $m) {
            TeamMember::firstOrCreate(['name' => $m['name']], $m);
        }
    }
}
