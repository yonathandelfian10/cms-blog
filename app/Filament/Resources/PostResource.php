<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Models\Post;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Set;
use Illuminate\Support\Str;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // --- SECTION KIRI (Konten Utama) ---
                Forms\Components\Section::make('Konten Utama')->schema([
                    Forms\Components\TextInput::make('title')
                        ->required()
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn($set, $state) => $set('slug', Str::slug($state))),

                    Forms\Components\TextInput::make('slug')
                        ->disabled()
                        ->dehydrated()
                        ->unique(ignoreRecord: true),

                    Forms\Components\RichEditor::make('content')
                        ->required()
                        ->columnSpanFull(),
                ])->columnSpan(2), // Lebar 2 kolom

                // --- SECTION KANAN (Meta Data) ---
                Forms\Components\Section::make('Meta Data')->schema([
                    // 1. Relasi ke Category
                    Forms\Components\Select::make('category_id')
                        ->relationship('category', 'name')
                        ->required()
                        ->label('Kategori'),

                    // 2. Relasi ke User (Penulis) - Tetap ada
                    Forms\Components\Select::make('user_id')
                        ->relationship('user', 'name')
                        ->required()
                        ->label('Penulis'),

                    // 3. Status Post (Draft/Published)
                    Forms\Components\Select::make('status')
                        ->options([
                            'draft' => 'Draft',
                            'published' => 'Published',
                        ])
                        ->default('draft')
                        ->required(),

                    // 4. Upload Gambar (Sesuai PRD)
                    Forms\Components\FileUpload::make('cover_file_url')
                        ->image()
                        ->directory('posts-covers') // Folder penyimpanan
                        ->label('Cover Image'),

                    // 5. Tanggal Publish
                    Forms\Components\DateTimePicker::make('published_at'),

                ])->columnSpan(1), // Lebar 1 kolom
            ])->columns(3); // Total grid 3 kolom
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // 1. Cover Image
                Tables\Columns\ImageColumn::make('cover_file_url')
                    ->label('Cover'),

                // 2. Judul
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->limit(30)
                    ->sortable(),

                // 3. Kategori (Badge Warna)
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->badge()
                    ->color('info') // Warna biru muda
                    ->sortable(),

                // 4. Penulis
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Penulis')
                    ->sortable(),

                // 5. Status (Badge Hijau/Abu)
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'draft' => 'gray',
                        'published' => 'success',
                    }),

                // 6. Views (Analisa Performa)
                Tables\Columns\TextColumn::make('view_count')
                    ->label('Views')
                    ->icon('heroicon-m-eye')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            // --- BAGIAN INI YANG MEMUNCULKAN TOMBOL EDIT/DELETE ---
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            // ------------------------------------------------------
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
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
