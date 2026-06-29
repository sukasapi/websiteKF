<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\HtmlString;

class ManageSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationGroup = 'Pengaturan';

    protected static ?string $title = 'Pengaturan Situs';

    protected static ?string $navigationLabel = 'Pengaturan Situs';

    protected static ?int $navigationSort = 2;

    protected static string $view = 'filament.pages.manage-settings';

    public ?array $data = [];

    /**
     * Daftar key pengaturan yang dikelola.
     */
    protected array $keys = [
        'site_name', 'site_logo', 'site_tagline_id', 'site_tagline_en',
        'contact_email', 'contact_phone', 'whatsapp', 'address',
        'animation_studio_url', 'social_instagram', 'social_linkedin',
        'hero_title_id', 'hero_title_en', 'hero_subtitle_id', 'hero_subtitle_en',
        // Email pengiriman (SMTP)
        'mail_mailer', 'mail_host', 'mail_port', 'mail_username', 'mail_password',
        'mail_encryption', 'mail_from_address', 'mail_from_name', 'webmail_url',
        // Auto-reply form kontak
        'contact_autoreply_enabled', 'contact_autoreply_subject', 'contact_autoreply_message',
        // Template balasan cepat (disimpan sebagai JSON)
        'contact_reply_templates',
    ];

    public static function canAccess(): bool
    {
        return auth()->user()?->hasRole('Admin') ?? false;
    }

    public function mount(): void
    {
        $values = [];
        foreach ($this->keys as $key) {
            $values[$key] = Setting::get($key);
        }
        // Jangan kirim password SMTP yang tersimpan ke browser; biarkan kosong.
        // (Field kosong saat disimpan = password lama dipertahankan.)
        $values['mail_password'] = null;

        // Template balasan disimpan sebagai JSON → ubah ke array untuk Repeater.
        $rawTemplates = $values['contact_reply_templates'] ?? null;
        $values['contact_reply_templates'] = is_array($rawTemplates)
            ? $rawTemplates
            : (json_decode($rawTemplates ?? '[]', true) ?: []);

        $this->form->fill($values);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Identitas Situs')->schema([
                    TextInput::make('site_name')->label('Nama Situs')->required(),
                    FileUpload::make('site_logo')->label('Logo')->image()->directory('settings'),
                    TextInput::make('site_tagline_id')->label('Tagline (ID)'),
                    TextInput::make('site_tagline_en')->label('Tagline (EN)'),
                ])->columns(2),

                Section::make('Hero Beranda')->schema([
                    TextInput::make('hero_title_id')->label('Judul Hero (ID)'),
                    TextInput::make('hero_title_en')->label('Judul Hero (EN)'),
                    Textarea::make('hero_subtitle_id')->label('Subjudul Hero (ID)')->rows(2),
                    Textarea::make('hero_subtitle_en')->label('Subjudul Hero (EN)')->rows(2),
                ])->columns(2),

                Section::make('Kontak')->schema([
                    TextInput::make('contact_email')->label('Email')->email(),
                    TextInput::make('contact_phone')->label('Telepon'),
                    TextInput::make('whatsapp')->label('WhatsApp (628xxx)'),
                    TextInput::make('address')->label('Alamat'),
                ])->columns(2),

                Section::make('Tautan')->schema([
                    TextInput::make('animation_studio_url')->label('URL Studio Animasi')->url(),
                    TextInput::make('social_instagram')->label('Instagram')->url(),
                    TextInput::make('social_linkedin')->label('LinkedIn')->url(),
                ])->columns(2),

                Section::make('Email Pengiriman (SMTP)')
                    ->description('Konfigurasi server email untuk mengirim notifikasi (mis. pesan kontak). Kosongkan untuk memakai konfigurasi default dari file .env.')
                    ->icon('heroicon-o-envelope')
                    ->collapsible()
                    ->schema([
                        Placeholder::make('smtp_status')
                            ->label('Status konfigurasi')
                            ->content(fn () => $this->smtpStatusBadge())
                            ->columnSpanFull(),
                        Select::make('mail_mailer')
                            ->label('Driver')
                            ->options([
                                'smtp' => 'SMTP',
                                'log'  => 'Log (uji coba — email ditulis ke log)',
                            ])
                            ->default('smtp')
                            ->native(false),
                        TextInput::make('mail_host')
                            ->label('Host SMTP')
                            ->placeholder('mail.domainanda.com')
                            ->requiredWith('mail_username'),
                        TextInput::make('mail_port')
                            ->label('Port')
                            ->numeric()
                            ->placeholder('587')
                            ->helperText('Umumnya 587 (TLS) atau 465 (SSL).'),
                        Select::make('mail_encryption')
                            ->label('Enkripsi')
                            ->options([
                                'tls'  => 'TLS',
                                'ssl'  => 'SSL',
                                'none' => 'Tanpa enkripsi',
                            ])
                            ->default('tls')
                            ->native(false),
                        TextInput::make('mail_username')
                            ->label('Username')
                            ->placeholder('noreply@domainanda.com')
                            ->autocomplete('off'),
                        TextInput::make('mail_password')
                            ->label('Password')
                            ->password()
                            ->revealable()
                            ->autocomplete('new-password')
                            ->helperText('Tersimpan terenkripsi pada server. Biarkan terisi untuk mempertahankan password lama.'),
                        TextInput::make('mail_from_address')
                            ->label('Email Pengirim (From)')
                            ->email()
                            ->placeholder('noreply@domainanda.com'),
                        TextInput::make('mail_from_name')
                            ->label('Nama Pengirim (From)')
                            ->placeholder('Kurnia Fedora'),
                        TextInput::make('webmail_url')
                            ->label('URL Webmail (opsional)')
                            ->url()
                            ->maxLength(255)
                            ->placeholder('https://mail.domainanda.com')
                            ->helperText('Tautan webmail hosting Anda — dipakai tombol "Buka Email" di pojok kanan atas. Kosongkan untuk memakai aplikasi email default (mailto).')
                            ->columnSpanFull(),
                        Placeholder::make('mail_hint')
                            ->label('')
                            ->content('Setelah menyimpan, gunakan tombol "Kirim Email Uji" di pojok kanan atas untuk memastikan konfigurasi berfungsi.')
                            ->columnSpanFull(),
                    ])->columns(2),

                Section::make('Balasan Otomatis (Auto-reply)')
                    ->description('Kirim email balasan otomatis ke pengunjung yang mengisi form kontak. Membutuhkan konfigurasi email yang aktif.')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        Toggle::make('contact_autoreply_enabled')
                            ->label('Aktifkan balasan otomatis')
                            ->helperText('Bila aktif, pengirim form kontak akan menerima email konfirmasi.')
                            ->columnSpanFull(),
                        TextInput::make('contact_autoreply_subject')
                            ->label('Subjek')
                            ->placeholder('Terima kasih telah menghubungi kami')
                            ->columnSpanFull(),
                        Textarea::make('contact_autoreply_message')
                            ->label('Isi pesan')
                            ->rows(5)
                            ->placeholder("Halo {name},\n\nTerima kasih telah menghubungi kami. Pesan Anda sudah kami terima dan tim kami akan segera menghubungi Anda.")
                            ->helperText('Gunakan {name} untuk menyisipkan nama pengirim secara otomatis.')
                            ->columnSpanFull(),
                    ])->columns(1),

                Section::make('Template Balasan Cepat')
                    ->description('Balasan siap pakai yang bisa dipilih saat membalas pesan kontak di menu "Pesan Kontak".')
                    ->icon('heroicon-o-document-duplicate')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        Repeater::make('contact_reply_templates')
                            ->label('')
                            ->addActionLabel('Tambah template')
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['label'] ?? null)
                            ->schema([
                                TextInput::make('label')
                                    ->label('Judul template')
                                    ->required()
                                    ->maxLength(100)
                                    ->placeholder('Terima kasih'),
                                Textarea::make('body')
                                    ->label('Isi balasan')
                                    ->required()
                                    ->rows(4)
                                    ->maxLength(5000)
                                    ->placeholder("Halo {name},\n\nTerima kasih telah menghubungi {site}…")
                                    ->helperText('Placeholder: {name} = nama pengirim, {site} = nama situs.'),
                            ])
                            ->columns(1)
                            ->defaultItems(0),
                    ]),
            ])
            ->statePath('data');
    }

    /**
     * Badge status konfigurasi SMTP (berdasarkan pengaturan tersimpan).
     */
    protected function smtpStatusBadge(): HtmlString
    {
        $base = 'display:inline-flex;align-items:center;gap:.4rem;padding:.25rem .7rem;border-radius:9999px;font-size:.8rem;font-weight:600;';

        if (Setting::get('mail_mailer') === 'log') {
            return new HtmlString(
                '<span style="'.$base.'background:#fef0bf;color:#714811;">● Mode Uji (Log)</span>'
                .' <span style="color:#6b7280;font-size:.8rem;">Email ditulis ke <code>storage/logs</code>, tidak benar-benar terkirim.</span>'
            );
        }

        if (Setting::get('mail_host')) {
            return new HtmlString(
                '<span style="'.$base.'background:#d1fae5;color:#065f46;">● SMTP Aktif</span>'
                .' <span style="color:#6b7280;font-size:.8rem;">Host: <code>'.e(Setting::get('mail_host')).':'.e(Setting::get('mail_port') ?: 587).'</code></span>'
            );
        }

        return new HtmlString(
            '<span style="'.$base.'background:#fee2e2;color:#991b1b;">● Belum dikonfigurasi</span>'
            .' <span style="color:#6b7280;font-size:.8rem;">Saat ini memakai konfigurasi default dari file <code>.env</code>.</span>'
        );
    }

    /**
     * Tombol aksi di header halaman.
     */
    protected function getHeaderActions(): array
    {
        return [
            Action::make('openMail')
                ->label('Buka Email')
                ->icon('heroicon-o-inbox-arrow-down')
                ->color('gray')
                ->url(fn () => Setting::get('webmail_url') ?: 'mailto:'.Setting::get('contact_email'))
                ->openUrlInNewTab()
                ->visible(fn () => filled(Setting::get('webmail_url')) || filled(Setting::get('contact_email'))),

            Action::make('sendTest')
                ->label('Kirim Email Uji')
                ->icon('heroicon-o-paper-airplane')
                ->color('gray')
                ->form([
                    TextInput::make('test_email')
                        ->label('Kirim ke alamat')
                        ->email()
                        ->required()
                        ->default(fn () => Setting::get('contact_email')),
                ])
                ->action(function (array $data): void {
                    // Terapkan konfigurasi dari form saat ini (termasuk perubahan yang belum disimpan).
                    $this->applyMailConfig($this->data);

                    try {
                        $siteName = Setting::get('site_name', 'Kurnia Fedora');

                        Mail::raw(
                            "Halo,\n\nIni adalah email uji dari {$siteName}.\n".
                            "Jika Anda menerima pesan ini, berarti konfigurasi SMTP Anda sudah berfungsi dengan baik.\n",
                            function ($message) use ($data, $siteName) {
                                $message->to($data['test_email'])
                                    ->subject("Email Uji — {$siteName}");
                            }
                        );

                        Notification::make()
                            ->title('Email uji terkirim')
                            ->body('Berhasil dikirim ke '.$data['test_email'].'. Silakan periksa kotak masuk.')
                            ->success()
                            ->send();
                    } catch (\Throwable $e) {
                        Notification::make()
                            ->title('Gagal mengirim email uji')
                            ->body($e->getMessage())
                            ->danger()
                            ->persistent()
                            ->send();
                    }
                }),
        ];
    }

    public function save(): void
    {
        foreach ($this->form->getState() as $key => $value) {
            if ($key === 'mail_password') {
                // Field kosong → pertahankan password lama. Jika diisi → simpan terenkripsi.
                if ($value === null || $value === '') {
                    continue;
                }

                Setting::set($key, Crypt::encryptString($value));
                continue;
            }

            // Nilai array (mis. Repeater template) disimpan sebagai JSON.
            if (is_array($value)) {
                Setting::set($key, json_encode(array_values($value)));
                continue;
            }

            Setting::set($key, $value);
        }

        Notification::make()->title('Pengaturan disimpan')->success()->send();
    }

    /**
     * Terapkan konfigurasi mailer dari array setting ke config runtime,
     * sehingga email uji bisa memakai nilai terbaru di form.
     */
    protected function applyMailConfig(array $d): void
    {
        if (! empty($d['mail_mailer'])) {
            config(['mail.default' => $d['mail_mailer']]);
        }

        if (! empty($d['mail_host'])) {
            $enc = $d['mail_encryption'] ?? null;
            // Field password kosong → pakai password yang sudah tersimpan (didekripsi).
            $password = ! empty($d['mail_password']) ? $d['mail_password'] : Setting::mailPassword();

            config([
                'mail.mailers.smtp.host'       => $d['mail_host'],
                'mail.mailers.smtp.port'       => (int) ($d['mail_port'] ?? 587),
                'mail.mailers.smtp.username'   => $d['mail_username'] ?? null,
                'mail.mailers.smtp.password'   => $password,
                'mail.mailers.smtp.encryption' => ($enc && $enc !== 'none') ? $enc : null,
            ]);
        }

        if (! empty($d['mail_from_address'])) {
            config([
                'mail.from.address' => $d['mail_from_address'],
                'mail.from.name'    => $d['mail_from_name'] ?? Setting::get('site_name', 'Kurnia Fedora'),
            ]);
        }
    }
}
