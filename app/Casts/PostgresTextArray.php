<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class PostgresTextArray implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): array
    {
        if ($value === null || $value === '{}') {
            return [];
        }

        $value = trim($value, '{}');
        $items = str_getcsv($value, ',', '"', '\\');

        return array_values(array_filter($items, static fn (string $item): bool => $item !== ''));
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): string
    {
        $items = is_array($value) ? $value : [];

        $items = array_map(static function (mixed $item): string {
            $item = (string) $item;

            return '"'.str_replace(['\\', '"'], ['\\\\', '\\"'], $item).'"';
        }, $items);

        return '{'.implode(',', $items).'}';
    }
}
