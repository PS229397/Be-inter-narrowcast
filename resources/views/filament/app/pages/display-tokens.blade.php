<x-filament-panels::page>
    <div class="space-y-4">
        @php $tokens = $this->getTokens() @endphp

        @if ($tokens->isEmpty())
            <x-filament::section>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    No active display tokens. Screens will be prompted to log in when they next connect.
                </p>
            </x-filament::section>
        @else
            <x-filament::section>
                <x-slot name="heading">Active Tokens ({{ $tokens->count() }})</x-slot>

                <div class="divide-y divide-gray-200 dark:divide-white/10">
                    @foreach ($tokens as $token)
                        <div class="flex items-center justify-between gap-4 py-3">
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $token->name ?? 'Unknown device' }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    Created {{ $token->created_at->diffForHumans() }}
                                    &middot;
                                    @if ($token->last_used_at)
                                        Last used {{ $token->last_used_at->diffForHumans() }}
                                    @else
                                        Never used
                                    @endif
                                    &middot;
                                    Expires {{ $token->expires_at->format('d M Y') }}
                                </p>
                            </div>

                            <x-filament::button
                                color="danger"
                                size="sm"
                                wire:click="revokeToken({{ $token->id }})"
                                wire:confirm="Revoke this token? The screen will need to log in again."
                            >
                                Revoke
                            </x-filament::button>
                        </div>
                    @endforeach
                </div>
            </x-filament::section>
        @endif
    </div>
</x-filament-panels::page>
