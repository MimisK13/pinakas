<?php

namespace Mimisk\Pinakas\Columns;

use Closure;

class Column
{
    public string $name;

    public string $attribute;

    public bool $sortable = false;

    public bool $searchable = false;

    protected string $align = 'left';

    protected string $headerAlign = 'left';

    protected ?Closure $formatter = null;

    public function __construct(?string $name = null, string $attribute = '')
    {
        $this->name = $this->resolveName($name, $attribute);
        $this->attribute = $attribute;
    }

    public static function make(?string $name = null, string $attribute = ''): self
    {
        return new self($name, $attribute);
    }

    public function sortable(): self
    {
        $this->sortable = true;

        return $this;
    }

    public function searchable(): self
    {
        $this->searchable = true;

        return $this;
    }

    public function align(string $align = 'left'): self
    {
        $normalized = strtolower(trim($align));
        $resolved = in_array($normalized, ['left', 'center', 'right'], true) ? $normalized : 'left';
        $this->align = $resolved;
        $this->headerAlign = $resolved;

        return $this;
    }

    public function headerAlign(string $align = 'left'): self
    {
        $normalized = strtolower(trim($align));
        $this->headerAlign = in_array($normalized, ['left', 'center', 'right'], true) ? $normalized : 'left';

        return $this;
    }

    public function cellAlign(string $align = 'left'): self
    {
        $normalized = strtolower(trim($align));
        $this->align = in_array($normalized, ['left', 'center', 'right'], true) ? $normalized : 'left';

        return $this;
    }

    public function formatUsing(callable $formatter): self
    {
        $this->formatter = $formatter(...);

        return $this;
    }

    public function getValueFromRow(mixed $row): mixed
    {
        if ($this->attribute === '') {
            return null;
        }

        return data_get($row, $this->attribute);
    }

    public function formatValue(mixed $value, mixed $row): mixed
    {
        if ($this->formatter !== null) {
            return ($this->formatter)($value, $row, $this);
        }

        return $value;
    }

    public function headerAlignClass(): string
    {
        return match ($this->headerAlign) {
            'center' => 'text-center',
            'right' => 'text-right',
            default => 'text-left',
        };
    }

    public function cellAlignClass(): string
    {
        return match ($this->align) {
            'center' => 'text-center',
            'right' => 'text-right',
            default => 'text-left',
        };
    }

    public function alignValue(): string
    {
        return $this->align;
    }

    public function headerAlignValue(): string
    {
        return $this->headerAlign;
    }

    protected function resolveName(?string $name, string $attribute): string
    {
        $trimmed = trim((string) $name);
        if ($trimmed !== '') {
            return $trimmed;
        }

        $attributeTrimmed = trim($attribute);
        if ($attributeTrimmed === '') {
            return '';
        }

        return ucwords(str_replace(['_', '.'], ' ', $attributeTrimmed));
    }
}
