<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-users';
    }
    
    // Mengelompokkan menu agar rapi di sidebar
    public static function getNavigationGroup(): ?string
    {
        return 'Manajemen Sistem';
    }

    /**
     * PEMBATASAN AKSES:
     * Fungsi ini memastikan bahwa menu "Users" di sidebar hanya 
     * muncul dan bisa diakses oleh pengguna dengan role 'admin'.
     */
    public static function canViewAny(): bool
    {
        return auth()->user()->role === 'admin';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([ // Catatan: Jika ini memunculkan peringatan, ganti ->schema() menjadi ->components() sesuai standar versi terbaru
                // ... (isi field TextInput dan Select tetap biarkan sama)
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->label('Nama Lengkap'),
                    
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                    
                Forms\Components\Select::make('role')
                    ->options([
                        'admin' => 'Admin (Pengelola)',
                        'user' => 'User (Pengaju/Peneliti)',
                    ])
                    ->required()
                    ->default('user')
                    ->label('Hak Akses'),
                    
                Forms\Components\TextInput::make('password')
                    ->password()
                    // Mengenkripsi password secara otomatis sebelum disimpan ke database
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    // Hanya perbarui password jika kolom ini diisi (saat edit)
                    ->dehydrated(fn ($state) => filled($state))
                    // Password hanya wajib diisi saat membuat user baru
                    ->required(fn (string $context): bool => $context === 'create')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->label('Nama'),
                    
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('role')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'admin' => 'danger',  // Admin berwarna merah
                        'user' => 'success',  // User berwarna hijau
                        default => 'gray',
                    })
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Anda bisa menambahkan filter khusus di sini nantinya
            ])
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
            // Tempat untuk mendefinisikan relasi antar tabel (misal: riwayat pengajuan user ini)
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