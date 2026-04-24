<?php

namespace Mimisk\Pinakas\Columns;

use Illuminate\Support\HtmlString;

class BadgeColumn extends Column
{
    private string $defaultColor = 'gray';

    /** @var callable|string|null */
    private mixed $colorResolver = null;

    public static function make(?string $name = null, string $attribute = ''): self
    {
        return new self($name, $attribute);
    }

    public function color(string|callable $color): self
    {
        $this->colorResolver = $color;

        return $this;
    }

    public function formatValue(mixed $value, mixed $row): mixed
    {
        $formatted = parent::formatValue($value, $row);

        if ($formatted === null || $formatted === '') {
            return '';
        }

        $text = is_scalar($formatted) ? (string) $formatted : json_encode($formatted);
        $color = $this->resolveColor($value, $row);
        $class = $this->badgeClass($color);

        return new HtmlString('<span class="' . e($class) . '">' . e($text) . '</span>');
    }

    private function resolveColor(mixed $value, mixed $row): string
    {
        if (is_callable($this->colorResolver)) {
            $resolved = (string) call_user_func($this->colorResolver, $value, $row, $this);

            return $this->normalizeColor($resolved);
        }

        if (is_string($this->colorResolver) && trim($this->colorResolver) !== '') {
            return $this->normalizeColor($this->colorResolver);
        }

        return $this->defaultColor;
    }

    private function normalizeColor(string $color): string
    {
        $normalized = strtolower(trim($color));

        return in_array($normalized, ['gray', 'red', 'amber', 'green', 'blue', 'indigo'], true)
            ? $normalized
            : $this->defaultColor;
    }

    private function badgeClass(string $color): string
    {
        return match ($color) {
            'red' => 'inline-flex items-center rounded-md bg-red-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/20 dark:bg-red-500/10 dark:text-red-300 dark:ring-red-500/20',
            'amber' => 'inline-flex items-center rounded-md bg-amber-50 px-2 py-1 text-xs font-medium text-amber-700 ring-1 ring-inset ring-amber-600/20 dark:bg-amber-500/10 dark:text-amber-300 dark:ring-amber-500/20',
            'green' => 'inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20 dark:bg-green-500/10 dark:text-green-300 dark:ring-green-500/20',
            'blue' => 'inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-600/20 dark:bg-blue-500/10 dark:text-blue-300 dark:ring-blue-500/20',
            'indigo' => 'inline-flex items-center rounded-md bg-indigo-50 px-2 py-1 text-xs font-medium text-indigo-700 ring-1 ring-inset ring-indigo-600/20 dark:bg-indigo-500/10 dark:text-indigo-300 dark:ring-indigo-500/20',
            default => 'inline-flex items-center rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-700 ring-1 ring-inset ring-gray-600/20 dark:bg-gray-500/10 dark:text-gray-300 dark:ring-gray-500/20',
        };
    }
}
