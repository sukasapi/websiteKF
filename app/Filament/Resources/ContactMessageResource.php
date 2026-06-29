<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactMessageResource\Pages;
use App\Models\ContactMessage;
use App\Notifications\ContactReply;
use Filament\Forms;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Notification as MailNotification;

class ContactMessageResource extends Resource
{
    protected static ?string $model = ContactMessage::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static ?string $navigationGroup = 'Pesan';

    protected static ?string $modelLabel = 'Pesan Kontak';

    protected static ?string $pluralModelLabel = 'Pesan Kontak';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::where('is_read', false)->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Infolists\Components\TextEntry::make('name')->label('Nama'),
            Infolists\Components\TextEntry::make('email')->label('Email')->copyable(),
            Infolists\Components\TextEntry::make('subject')->label('Subjek'),
            Infolists\Components\TextEntry::make('message')->label('Pesan')->columnSpanFull(),
            Infolists\Components\TextEntry::make('created_at')->label('Diterima')->dateTime('d M Y H:i'),
            Infolists\Components\Section::make('Balasan')
                ->visible(fn (ContactMessage $record) => filled($record->reply))
                ->schema([
                    Infolists\Components\TextEntry::make('reply')->label('Isi balasan')->columnSpanFull(),
                    Infolists\Components\TextEntry::make('replied_at')->label('Dibalas pada')->dateTime('d M Y H:i'),
                    Infolists\Components\TextEntry::make('repliedBy.name')
                        ->label('Dibalas oleh')
                        ->placeholder('—'),
                ])->columns(2),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\IconColumn::make('is_read')->label('Dibaca')->boolean(),
                Tables\Columns\IconColumn::make('replied_at')
                    ->label('Dibalas')
                    ->boolean()
                    ->getStateUsing(fn (ContactMessage $record) => $record->replied_at !== null),
                Tables\Columns\TextColumn::make('name')->label('Nama')->searchable(),
                Tables\Columns\TextColumn::make('email')->label('Email')->searchable(),
                Tables\Columns\TextColumn::make('subject')->label('Subjek')->limit(30),
                Tables\Columns\TextColumn::make('created_at')->label('Diterima')->dateTime('d M Y H:i')->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_read')->label('Status Dibaca'),
            ])
            ->actions([
                Tables\Actions\Action::make('reply')
                    ->label('Balas')
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->color('primary')
                    ->modalHeading('Balas Pesan Kontak')
                    ->modalSubmitActionLabel('Kirim Balasan')
                    ->modalWidth('2xl')
                    ->form([
                        Forms\Components\Placeholder::make('to')
                            ->label('Kepada')
                            ->content(fn (ContactMessage $record) => $record->name.' <'.$record->email.'>'),
                        Forms\Components\Placeholder::make('original')
                            ->label('Pesan asli')
                            ->content(fn (ContactMessage $record) => $record->message),
                        Forms\Components\Select::make('template')
                            ->label('Template balasan (opsional)')
                            ->placeholder('Pilih untuk mengisi otomatis…')
                            ->options(fn () => collect(static::replyTemplates())->pluck('label', 'label'))
                            ->visible(fn () => filled(static::replyTemplates()))
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set, ContactMessage $record): void {
                                $tpl = collect(static::replyTemplates())->firstWhere('label', $state);
                                if ($tpl) {
                                    $set('reply', static::renderTemplate($tpl['body'] ?? '', $record));
                                }
                            }),
                        Forms\Components\Textarea::make('reply')
                            ->label('Isi balasan')
                            ->rows(7)
                            ->required()
                            ->maxLength(5000)
                            ->default(fn (ContactMessage $record) => $record->reply),
                    ])
                    ->action(function (ContactMessage $record, array $data): void {
                        try {
                            MailNotification::route('mail', $record->email)
                                ->notify(new ContactReply($record, $data['reply']));

                            $record->update([
                                'reply'      => $data['reply'],
                                'replied_at' => now(),
                                'replied_by' => auth()->id(),
                                'is_read'    => true,
                            ]);

                            Notification::make()
                                ->title('Balasan terkirim')
                                ->body('Email balasan dikirim ke '.$record->email)
                                ->success()
                                ->send();
                        } catch (\Throwable $e) {
                            Notification::make()
                                ->title('Gagal mengirim balasan')
                                ->body('Periksa konfigurasi SMTP. '.$e->getMessage())
                                ->danger()
                                ->persistent()
                                ->send();
                        }
                    }),
                Tables\Actions\ViewAction::make()
                    ->after(fn (ContactMessage $record) => $record->update(['is_read' => true])),
                Tables\Actions\Action::make('toggleRead')
                    ->label(fn (ContactMessage $record) => $record->is_read ? 'Tandai Belum Dibaca' : 'Tandai Dibaca')
                    ->icon('heroicon-o-check')
                    ->action(fn (ContactMessage $record) => $record->update(['is_read' => ! $record->is_read])),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContactMessages::route('/'),
        ];
    }

    /**
     * Daftar template balasan cepat (dikelola di halaman Pengaturan Situs).
     * Format tersimpan sebagai JSON: [{label, body}, ...].
     */
    public static function replyTemplates(): array
    {
        $raw = \App\Models\Setting::get('contact_reply_templates');

        if (! $raw) {
            return [];
        }

        $items = is_array($raw) ? $raw : json_decode($raw, true);

        return collect($items ?? [])
            ->filter(fn ($i) => filled($i['label'] ?? null) && filled($i['body'] ?? null))
            ->values()
            ->all();
    }

    /**
     * Ganti placeholder {name} / {site} pada body template.
     */
    public static function renderTemplate(string $body, ContactMessage $record): string
    {
        return str_replace(
            ['{name}', '{site}'],
            [$record->name, \App\Models\Setting::get('site_name', 'Kurnia Fedora')],
            $body
        );
    }
}
