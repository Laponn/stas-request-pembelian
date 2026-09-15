<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// 1. Import class yang dibutuhkan oleh Filament
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

// 2. Tambahkan 'role' ke dalam Fillable agar bisa diperbarui (contoh: saat diubah via Tinker)
#[Fillable(['name', 'email', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]

// 3. Implementasikan interface FilamentUser pada class
class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function requestPembelians()
    {
        return $this->hasMany(RequestPembelian::class);
    }

    /**
     * 4. Fungsi wajib dari FilamentUser untuk membatasi akses panel
     *
     * @param Panel $panel
     * @return bool
     */
    public function canAccessPanel(Panel $panel): bool
    {
        // Izinkan masuk ke panel (dashboard) jika role-nya 'admin' atau 'user'
        return in_array($this->role, ['admin', 'user']);
    }
}