<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // 1. Input Nama
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),

                // 2. Input Email
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),

                // 3. Input Nomor HP (Fitur Request Kamu)
                Forms\Components\TextInput::make('phone_number')
                    ->tel() // Validasi format telepon
                    ->label('Nomor HP')
                    ->maxLength(20),

                // 4. Input Password (Dengan Logika Hashing Aman)
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->label('Password')
                    // Wajib diisi HANYA saat membuat user baru
                    ->required(fn(string $operation): bool => $operation === 'create')
                    // Hash password sebelum disimpan
                    ->dehydrateStateUsing(fn(string $state): string => Hash::make($state))
                    // Hanya kirim ke database jika kolom diisi (supaya edit aman)
                    ->dehydrated(fn(?string $state): bool => filled($state))
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // 1. Menampilkan Nama
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Lengkap') // Label Header Tabel
                    ->searchable()          // Agar bisa dicari
                    ->sortable(),           // Agar bisa diurutkan A-Z

                // 2. Menampilkan Email
                Tables\Columns\TextColumn::make('email')
                    ->icon('heroicon-m-envelope') // Ikon surat kecil
                    ->copyable(),                 // Agar bisa diklik copy

                // 3. Menampilkan No HP (Fitur Request Kamu)
                Tables\Columns\TextColumn::make('phone_number')
                    ->label('Nomor HP')
                    ->default('-'), // Kalau kosong, tampilkan strip

                // 4. Menampilkan Tanggal Dibuat (Opsional tapi bagus)
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true), // Sembunyi default
            ])
            ->filters([
                //
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
