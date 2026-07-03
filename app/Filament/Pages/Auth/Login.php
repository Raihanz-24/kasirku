<?php

namespace App\Filament\Pages\Auth;

use Filament\Actions\Action;
use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Validation\ValidationException;

class Login extends BaseLogin
{
    protected string $view = 'filament.pages.auth.login';

    public function hasLogo(): bool
    {
        return false;
    }

    public function getTitle(): string|Htmlable
    {
        return 'Masuk - Kasirku';
    }

    public function getHeading(): string|Htmlable|null
    {
        return null;
    }

    public function getSubheading(): string|Htmlable|null
    {
        return null;
    }

    protected function getEmailFormComponent(): Component
    {
        return TextInput::make('username')
            ->label('Username')
            ->placeholder('Masukkan username')
            ->required()
            ->autocomplete('username')
            ->autofocus();
    }

    protected function getPasswordFormComponent(): Component
    {
        return TextInput::make('password')
            ->label('Kata sandi')
            ->placeholder('Masukkan kata sandi')
            ->password()
            ->revealable()
            ->autocomplete('current-password')
            ->required();
    }

    protected function getRememberFormComponent(): Component
    {
        return Checkbox::make('remember')
            ->label('Ingat saya di browser ini')
            ->default(true);
    }

    /** @param array<string, mixed> $data */
    protected function getCredentialsFromFormData(array $data): array
    {
        return [
            'username' => $data['username'],
            'password' => $data['password'],
        ];
    }

    protected function throwFailureValidationException(): never
    {
        throw ValidationException::withMessages([
            'data.username' => 'Username atau kata sandi tidak sesuai.',
        ]);
    }

    protected function getAuthenticateFormAction(): Action
    {
        return Action::make('authenticate')
            ->label('Masuk ke Kasirku')
            ->icon('heroicon-o-arrow-right-end-on-rectangle')
            ->submit('authenticate');
    }
}
