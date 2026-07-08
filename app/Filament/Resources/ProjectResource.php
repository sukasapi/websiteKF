<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectResource\Pages;
use App\Filament\Resources\ProjectResource\RelationManagers\ImagesRelationManager;
use App\Models\Project;
use App\Models\ProjectCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ProjectResource extends Resource
{
    use Translatable;

    protected static ?string $model = Project::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-group';

    protected static ?string $navigationGroup = 'Portofolio';

    protected static ?string $modelLabel = 'Proyek';

    protected static ?string $pluralModelLabel = 'Proyek';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Informasi Proyek')->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Judul')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (Forms\Set $set, ?string $state) => $set('slug', Str::slug($state)))
                    ->maxLength(255),
                Forms\Components\TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                Forms\Components\TextInput::make('client')->label('Klien')->maxLength(255),
                Forms\Components\TextInput::make('year')->label('Tahun')->numeric()->minValue(2000)->maxValue(2100),
                Forms\Components\Select::make('project_category_id')
                    ->label('Kategori')
                    ->relationship('category', 'name')
                    ->getOptionLabelFromRecordUsing(fn (ProjectCategory $record): string => static::getCategoryOptionLabel($record))
                    ->searchable()
                    ->preload(),
                Forms\Components\TagsInput::make('tech_stack')
                    ->label('Teknologi')
                    ->placeholder('Tambah teknologi')
                    ->helperText('Tekan Enter untuk menambah.'),
            ])->columns(2),

            Forms\Components\Section::make('Deskripsi')->schema([
                Forms\Components\RichEditor::make('description')
                    ->label('Deskripsi')
                    ->required()
                    ->columnSpanFull(),
            ]),

            Forms\Components\Section::make('Media & Tautan')->schema([
                Forms\Components\FileUpload::make('cover_image')
                    ->label('Gambar Sampul')
                    ->image()
                    ->directory('projects')
                    ->imageEditor(),
                Forms\Components\TextInput::make('demo_url')->label('URL Demo')->url(),
                Forms\Components\Textarea::make('embed_script')
                    ->label('Script Aplikasi (Popup)')
                    ->placeholder('<iframe src="https://..." width="100%" height="600"></iframe>')
                    ->helperText('Tempel kode embed (iframe/script) aplikasi atau game. Jika diisi, halaman detail proyek akan menampilkan tombol untuk menjalankan aplikasi dalam popup.')
                    ->rows(6)
                    ->columnSpanFull(),
            ])->columns(2),

            Forms\Components\Section::make('Pengaturan')->schema([
                Forms\Components\Toggle::make('is_featured')->label('Tampilkan di Beranda (Unggulan)'),
                Forms\Components\TextInput::make('order')->label('Urutan')->numeric()->default(0),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('cover_image')->label('Sampul'),
                Tables\Columns\TextColumn::make('title')->label('Judul')->searchable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->formatStateUsing(fn (Project $record): ?string => $record->category ? static::getCategoryOptionLabel($record->category) : null)
                    ->badge(),
                Tables\Columns\TextColumn::make('year')->label('Tahun')->sortable(),
                Tables\Columns\IconColumn::make('is_featured')->label('Unggulan')->boolean(),
            ])
            ->defaultSort('order')
            ->filters([
                Tables\Filters\SelectFilter::make('project_category_id')
                    ->label('Kategori')
                    ->options(fn (): array => ProjectCategory::query()
                        ->get()
                        ->mapWithKeys(fn (ProjectCategory $category): array => [
                            $category->getKey() => static::getCategoryOptionLabel($category),
                        ])
                        ->all()),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            ImagesRelationManager::class,
        ];
    }

    protected static function getCategoryOptionLabel(ProjectCategory $category): string
    {
        foreach ([app()->getLocale(), config('app.fallback_locale')] as $locale) {
            $label = $category->getTranslation('name', $locale, false);

            if (filled($label)) {
                return $label;
            }
        }

        return collect($category->getTranslations('name'))->first(fn ($label) => filled($label)) ?? (string) $category->getKey();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }
}
