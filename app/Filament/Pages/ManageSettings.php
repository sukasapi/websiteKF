<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

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
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        foreach ($this->form->getState() as $key => $value) {
            Setting::set($key, $value);
        }

        Notification::make()->title('Pengaturan disimpan')->success()->send();
    }
}
