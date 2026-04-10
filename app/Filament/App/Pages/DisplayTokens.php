<?php

namespace App\Filament\App\Pages;

use App\Models\DisplayToken;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class DisplayTokens extends Page
{
    protected string $view = 'filament.app.pages.display-tokens';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ComputerDesktop;

    protected static string|UnitEnum|null $navigationGroup = 'Access';

    protected static ?string $navigationLabel = 'Display Access';

    protected static ?string $title = 'Display Access Tokens';

    protected static ?int $navigationSort = 10;

    public function getTokens(): \Illuminate\Database\Eloquent\Collection
    {
        return DisplayToken::where('customer_id', Auth::user()?->customer_id)
            ->orderByDesc('created_at')
            ->get();
    }

    public function revokeToken(int $id): void
    {
        DisplayToken::where('customer_id', Auth::user()?->customer_id)
            ->where('id', $id)
            ->delete();

        $this->dispatch('tokens-refreshed');
    }

    public function revokeAll(): void
    {
        DisplayToken::where('customer_id', Auth::user()?->customer_id)->delete();

        $this->dispatch('tokens-refreshed');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('revoke_all')
                ->label('Revoke All Tokens')
                ->icon(Heroicon::Trash)
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Revoke all display tokens?')
                ->modalDescription('All display screens will be required to log in again.')
                ->action(fn () => $this->revokeAll()),
        ];
    }
}
