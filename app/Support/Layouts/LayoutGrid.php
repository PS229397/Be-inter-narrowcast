<?php

namespace App\Support\Layouts;

use Illuminate\Support\Arr;

class LayoutGrid
{
    public const VERSION = 1;

    /**
     * @return array<string, mixed>
     */
    public static function empty(): array
    {
        return [
            'version' => static::VERSION,
            'id' => 'root',
            'direction' => null,
            'split' => 50,
            'children' => [],
            'component' => null,
            'componentType' => null,
            'componentConfig' => [],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function normalize(mixed $raw): array
    {
        $parsed = static::parse($raw);

        if (! is_array($parsed) || array_is_list($parsed)) {
            return static::empty();
        }

        $counter = 0;

        return static::normalizeNode($parsed, $counter, isRoot: true);
    }

    /**
     * @return array<int, string>
     */
    public static function validationErrors(mixed $raw): array
    {
        $parsed = static::parse($raw);

        if ($parsed === null || $parsed === [] || $parsed === '') {
            return [];
        }

        if (! is_array($parsed) || array_is_list($parsed)) {
            return ['The layout grid payload must be an object.'];
        }

        return static::validateNode($parsed, isRoot: true);
    }

    /**
     * @return array<int, string>
     */
    public static function assignedComponentKeys(mixed $raw): array
    {
        $grid = static::normalize($raw);
        $keys = [];

        static::walk($grid, function (array $node) use (&$keys): void {
            $component = Arr::get($node, 'component');

            if (is_string($component) && $component !== '') {
                $keys[] = $component;
            }
        });

        return array_values(array_unique($keys));
    }

    /**
     * @param  array<int, string>  $allowedKeys
     * @return array<int, string>
     */
    public static function invalidComponentKeys(mixed $raw, array $allowedKeys): array
    {
        return array_values(array_diff(static::assignedComponentKeys($raw), $allowedKeys));
    }

    /**
     * @return array<string, mixed>|null
     */
    protected static function parse(mixed $raw): ?array
    {
        if ($raw === null || $raw === '' || $raw === []) {
            return null;
        }

        if (is_string($raw)) {
            /** @var mixed $decoded */
            $decoded = json_decode($raw, true);

            return is_array($decoded) ? $decoded : null;
        }

        if (is_array($raw)) {
            return $raw;
        }

        $json = json_encode($raw);

        if ($json === false) {
            return null;
        }

        /** @var mixed $decoded */
        $decoded = json_decode($json, true);

        return is_array($decoded) ? $decoded : null;
    }

    /**
     * @param  array<string, mixed>  $node
     * @return array<string, mixed>
     */
    protected static function normalizeNode(array $node, int &$counter, bool $isRoot = false): array
    {
        $component = static::normalizeComponentKey($node['component'] ?? null);
        $componentConfig = is_array($node['componentConfig'] ?? null) ? $node['componentConfig'] : [];

        $normalized = [
            'id' => $isRoot ? 'root' : static::normalizeNodeId($node['id'] ?? null, $counter),
            'direction' => in_array($node['direction'] ?? null, ['h', 'v'], true) ? $node['direction'] : null,
            'split' => static::normalizeSplit($node['split'] ?? null),
            'children' => [],
            'component' => $component,
            'componentType' => LayoutComponentCatalog::resolveType($component),
            'componentConfig' => $componentConfig,
        ];

        if ($isRoot) {
            $normalized['version'] = static::VERSION;
        }

        if (is_array($node['children'] ?? null) && count($node['children']) > 0) {
            $normalized['children'] = collect($node['children'])
                ->take(2)
                ->filter(fn (mixed $child): bool => is_array($child) && ! array_is_list($child))
                ->map(fn (array $child): array => static::normalizeNode($child, $counter))
                ->values()
                ->all();
        }

        if (count($normalized['children']) !== 2) {
            $normalized['children'] = [];
            $normalized['direction'] = null;
        }

        if ($normalized['children'] !== []) {
            $normalized['component'] = null;
            $normalized['componentType'] = null;
            $normalized['componentConfig'] = [];
        }

        return $normalized;
    }

    /**
     * @param  array<string, mixed>  $node
     * @return array<int, string>
     */
    protected static function validateNode(array $node, bool $isRoot = false, string $path = 'root'): array
    {
        $errors = [];

        if ($isRoot && ($node['version'] ?? static::VERSION) !== static::VERSION && ($node['version'] ?? null) !== null) {
            $errors[] = 'The layout grid version is not supported.';
        }

        $children = $node['children'] ?? [];

        if (! is_array($children)) {
            return ['The layout grid children payload must be an array.'];
        }

        if ($children !== [] && count($children) !== 2) {
            $errors[] = "Node [{$path}] must contain exactly 2 children when split.";
        }

        if ($children !== [] && ! in_array($node['direction'] ?? null, ['h', 'v'], true)) {
            $errors[] = "Node [{$path}] must declare a split direction.";
        }

        if (($node['split'] ?? null) !== null && ! is_numeric($node['split'])) {
            $errors[] = "Node [{$path}] must use a numeric split percentage.";
        }

        if (is_numeric($node['split'] ?? null)) {
            $split = (float) $node['split'];

            if ($split < 5 || $split > 95) {
                $errors[] = "Node [{$path}] must use a split between 5 and 95.";
            }
        }

        $component = $node['component'] ?? null;

        if ($component !== null && (! is_string($component) || LayoutComponentCatalog::resolveType($component) === null)) {
            $errors[] = "Node [{$path}] contains an unsupported component key.";
        }

        if (($node['componentConfig'] ?? []) !== [] && ! is_array($node['componentConfig'])) {
            $errors[] = "Node [{$path}] must use an object-like component configuration payload.";
        }

        foreach ($children as $index => $child) {
            if (! is_array($child) || array_is_list($child)) {
                $errors[] = "Node [{$path}.children.{$index}] must be an object.";

                continue;
            }

            $errors = [...$errors, ...static::validateNode($child, path: "{$path}.children.{$index}")];
        }

        return array_values(array_unique($errors));
    }

    protected static function normalizeComponentKey(mixed $component): ?string
    {
        return is_string($component) && $component !== '' ? $component : null;
    }

    protected static function normalizeNodeId(mixed $id, int &$counter): string
    {
        if (is_string($id) && $id !== '' && $id !== 'root') {
            return $id;
        }

        $counter++;

        return 'n'.$counter;
    }

    protected static function normalizeSplit(mixed $split): int
    {
        if (! is_numeric($split)) {
            return 50;
        }

        return (int) max(5, min(95, round((float) $split)));
    }

    /**
     * @param  callable(array<string, mixed>): void  $callback
     */
    protected static function walk(array $node, callable $callback): void
    {
        $callback($node);

        foreach ($node['children'] ?? [] as $child) {
            if (is_array($child)) {
                static::walk($child, $callback);
            }
        }
    }
}
