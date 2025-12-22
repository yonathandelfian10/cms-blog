<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group'; // Ikon diganti biar lebih cocok (Grup User)

    protected static ?string $navigationLabel = 'Manajemen User'; // Label di Sidebar

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // --- SECTION 1: Informasi Dasar ---
                Forms\Components\Section::make('Informasi Pribadi')->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Nama Lengkap')
                        ->required()
                        ->maxLength(255),

                    Forms\Components\TextInput::make('email')
                        ->email()
                        ->required()
                        ->maxLength(255),

                    // Menambahkan input No HP agar sinkron dengan tabel
                    Forms\Components\TextInput::make('phone_number')
                        ->label('Nomor HP')
                        ->tel()
                        ->maxLength(20),
                ])->columns(2),

                // --- SECTION 2: Hak Akses & Keamanan ---
                Forms\Components\Section::make('Akses & Keamanan')->schema([

                    // 1. Pilih Role (Integrasi Filament Shield)
                    Forms\Components\Select::make('roles')
                        ->relationship('roles', 'name')
                        ->multiple()
                        ->preload()
                        ->searchable()
                        ->label('Role / Peran'),

                    // 2. Status Active/Inactive
                    Forms\Components\Toggle::make('is_active')
                        ->label('Status Aktif')
                        ->default(true)
                        ->helperText('Jika dimatikan, user tidak akan bisa login ke sistem.')
                        ->onColor('success')
                        ->offColor('danger'),

                    // 3. Password (Dengan Hashing)
                    Forms\Components\TextInput::make('password')
                        ->password()
                        ->dehydrateStateUsing(fn($state) => Hash::make($state))
                        ->dehydrated(fn($state) => filled($state))
                        ->required(fn(string $operation): bool => $operation === 'create')
                        ->label('Password Baru'),
                ])->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // 1. Nama
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                // 2. Email
                Tables\Columns\TextColumn::make('email')
                    ->icon('heroicon-m-envelope')
                    ->searchable()
                    ->copyable(),

                // 3. Role (Badge)
                Tables\Columns\TextColumn::make('roles.name')
                    ->label('Role')
                    ->badge()
                    ->color('info')
                    ->separator(','),

                // 4. Status Aktif (Switch)
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Aktif'),

                // 5. No HP
                Tables\Columns\TextColumn::make('phone_number')
                    ->label('No HP')
                    ->default('-'),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status User'),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}