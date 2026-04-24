<?php

namespace Mimisk\Pinakas\Columns;

use Illuminate\Support\HtmlString;

class BooleanColumn extends Column
{
    private string $trueLabel = 'Yes';
    private string $falseLabel = 'No';
    private string $trueColor = 'green';
    private string $falseColor = 'gray';

    public static function make(?string $name = null, string $attribute = ''): self
    {
        return new self($name, $attribute);
    }

    public function labels(string $trueLabel = 'Yes', string $falseLabel = 'No'): self
    {
        $this->trueLabel = trim($trueLabel) !== '' ? $trueLabel : 'Yes';
        $this->falseLabel = trim($falseLabel) !== '' ? $falseLabel : 'No';

        return $this;
    }

    public function colors(string $trueColor = 'green', string $falseColor = 'gray'): self
    {
        $this->trueColor = $this->normalizeColor($trueColor);
        $this->falseColor = $this->normalizeColor($falseColor);

        return $this;
    }

    public function formatValue(mixed $value, mixed $row): mixed
    {
        $resolvedValue = parent::formatValue($value, $row);
        $bool = $this->toBoolean($resolvedValue);
        $label = $bool ? $this->trueLabel : $this->falseLabel;
        $class = $this->badgeClass($bool ? $this->trueColor : $this->falseColor);

        return new HtmlString('<span class="' . e($class) . '">' . e((string) $label) . '</span>');
    }

    private function toBoolean(mixed $value): bool
    {
        if (is_bool($value)) {
            return $value;
        }

        if (is_int($value) || is_float($value)) {
            return (int) $value === 1;
        }

        $normalized = strtolower(trim((string) $value));

        return in_array($normalized, ['1', 'true', 'yes', 'on'], true);
    }

    private function normalizeColor(string $color): string
    {
        $normalized = strtolower(trim($color));

        return in_array($normalized, ['gray', 'red', 'amber', 'green', 'blue', 'indigo'], true)
            ? $normalized
            : 'gray';
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
