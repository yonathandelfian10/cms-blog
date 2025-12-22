<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventResource\Pages;
use App\Models\Event;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    // Ikon di sidebar (bisa diganti sesuai selera)
    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    // Label grup di sidebar
    protected static ?string $navigationGroup = 'Manajemen Konten';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Detail Event')->schema([
                    // 1. Judul Event
                    Forms\Components\TextInput::make('title')
                        ->required()
                        ->maxLength(255)
                        ->label('Nama Event'),

                    // 2. Tanggal & Waktu Event
                    Forms\Components\DateTimePicker::make('event_date')
                        ->required()
                        ->label('Tanggal Pelaksanaan'),

                    // 3. Penanggung Jawab (User)
                    Forms\Components\Select::make('user_id')
                        ->relationship('user', 'name')
                        ->required()
                        ->searchable()
                        ->label('Penanggung Jawab'),

                    // 4. Deskripsi Event
                    Forms\Components\Textarea::make('description')
                        ->columnSpanFull() // Agar lebar memenuhi layar
                        ->label('Deskripsi Singkat'),

                    // 5. Upload Poster
                    Forms\Components\FileUpload::make('poster_file_url')
                        ->image() // Validasi harus gambar
                        ->directory('events-posters') // Folder penyimpanan
                        ->label('Poster Event')
                        ->columnSpanFull(),
                ])->columns(2), // Layout 2 kolom
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // 1. Poster
                Tables\Columns\ImageColumn::make('poster_file_url')
                    ->label('Poster')
                    ->circular(),

                // 2. Judul Event
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                // 3. Tanggal
                Tables\Columns\TextColumn::make('event_date')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->label('Jadwal'),

                // 4. PIC (Penanggung Jawab)
                Tables\Columns\TextColumn::make('user.name')
                    ->label('PIC')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            // --- TOMBOL AKSI ---
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
        ];
    }
}