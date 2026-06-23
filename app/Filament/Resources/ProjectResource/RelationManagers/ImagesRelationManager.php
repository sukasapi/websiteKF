<?php

namespace App\Filament\Resources\ProjectResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ImagesRelationManager extends RelationManager
{
    protected static string $relationship = 'images';

    protected static ?string $title = 'Galeri Gambar';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\FileUpload::make('path')
                ->label('Gambar')
                ->image()
                ->directory('projects/gallery')
                ->required(),
            Forms\Components\TextInput::make('caption')->label('Keterangan')->maxLength(255),
            Forms\Components\TextInput::make('order')->label('Urutan')->numeric()->default(0),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->reorderable('order')
            ->defaultSort('order')
            ->columns([
                Tables\Columns\ImageColumn::make('path')->label('Gambar'),
                Tables\Columns\TextColumn::make('caption')->label('Keterangan'),
                Tables\Columns\TextColumn::make('order')->label('Urutan'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('Tambah Gambar'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}
