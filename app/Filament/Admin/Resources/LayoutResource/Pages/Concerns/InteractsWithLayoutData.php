<?php

namespace App\Filament\Admin\Resources\LayoutResource\Pages\Concerns;

use App\Filament\Admin\Resources\LayoutResource;
use App\Support\Layouts\LayoutGrid;
use Illuminate\Validation\ValidationException;

trait InteractsWithLayoutData
{
    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function normalizeLayoutData(array $data): array
    {
        $customerIds = collect((array) ($data['customers'] ?? []))
            ->filter(fn (mixed $id): bool => filled($id))
            ->map(fn (mixed $id): int => (int) $id)
            ->values()
            ->all();

        $data['customers'] = $customerIds;
        $data['grid'] = LayoutGrid::normalize($data['grid'] ?? null);

        $invalidComponentKeys = LayoutResource::getInvalidGridComponentKeys(
            grid: $data['grid'],
            customerIds: $customerIds,
        );

        if ($invalidComponentKeys !== []) {
            throw ValidationException::withMessages([
                'data.grid' => 'The layout contains unavailable components: '.implode(', ', $invalidComponentKeys).'.',
            ]);
        }

        return $data;
    }
}
