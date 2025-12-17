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

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
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
