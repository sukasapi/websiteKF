<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureMailFromSettings();
    }

    /**
     * Override konfigurasi mailer dengan nilai dari tabel settings
     * (diisi lewat halaman Pengaturan Situs di backoffice).
     * Aman dipanggil sebelum migrasi: jika tabel belum ada, dilewati.
     */
    protected function configureMailFromSettings(): void
    {
        try {
            if (! Schema::hasTable('settings')) {
                return;
            }
        } catch (\Throwable $e) {
            // Koneksi DB belum siap (mis. saat instalasi) — pakai config .env.
            return;
        }

        if ($mailer = Setting::get('mail_mailer')) {
            config(['mail.default' => $mailer]);
        }

        if ($host = Setting::get('mail_host')) {
            $enc = Setting::get('mail_encryption');

            config([
                'mail.mailers.smtp.host'       => $host,
                'mail.mailers.smtp.port'       => (int) (Setting::get('mail_port') ?: 587),
                'mail.mailers.smtp.username'   => Setting::get('mail_username') ?: null,
                'mail.mailers.smtp.password'   => Setting::mailPassword(),
                'mail.mailers.smtp.encryption' => ($enc && $enc !== 'none') ? $enc : null,
            ]);
        }

        if ($from = Setting::get('mail_from_address')) {
            config([
                'mail.from.address' => $from,
                'mail.from.name'    => Setting::get('mail_from_name') ?: Setting::get('site_name', config('mail.from.name')),
            ]);
        }
    }
}
