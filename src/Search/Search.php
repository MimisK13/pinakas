<?php

namespace Mimisk\Pinakas\Search;

use Illuminate\Database\Eloquent\Builder;

class Search
{
    public function __construct(
        private bool $enabled = false,
        private string $queryName = 'search',
        private bool $showLabel = true,
        private ?string $label = 'Search',
        private string $placeholder = 'Search...',
        private ?string $icon = 'magnifying-glass',
        private int $debounceMs = 350,
        private int $minChars = 3,
        private string $rounded = 'rounded-none',
    ) {
        $trimmed = trim($this->queryName);
        $this->queryName = $trimmed !== '' ? $trimmed : 'search';
        $this->label = $this->normalizeNullableText($this->label);
        $this->placeholder = $this->normalizeText($this->placeholder, 'Search...');
        $this->icon = $this->normalizeNullableText($this->icon);
        $this->debounceMs = $this->normalizeDebounceMs($this->debounceMs);
        $this->minChars = $this->normalizeMinChars($this->minChars);
        $this->rounded = $this->normalizeRounded($this->rounded);
    }

    public function enable(string $queryName = 'search'): self
    {
        $this->enabled = true;
        $trimmed = trim($queryName);
        $this->queryName = $trimmed !== '' ? $trimmed : 'search';

        return $this;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function queryName(): string
    {
        return $this->queryName;
    }

    public function setShowLabel(bool $show): self
    {
        $this->showLabel = $show;

        return $this;
    }

    public function showLabel(): bool
    {
        return $this->showLabel;
    }

    public function setLabel(?string $label): self
    {
        $this->label = $this->normalizeNullableText($label);

        return $this;
    }

    public function label(): ?string
    {
        return $this->label;
    }

    public function setPlaceholder(?string $placeholder): self
    {
        $this->placeholder = $this->normalizeText($placeholder, 'Search...');

        return $this;
    }

    public function placeholder(): string
    {
        return $this->placeholder;
    }

    public function setIcon(?string $icon): self
    {
        $this->icon = $this->normalizeNullableText($icon);

        return $this;
    }

    public function icon(): ?string
    {
        return $this->icon;
    }

    public function iconView(): ?string
    {
        if ($this->icon === null) {
            return null;
        }

        if (str_contains($this->icon, '::') || str_contains($this->icon, '.')) {
            return $this->icon;
        }

        return match ($this->icon) {
            'magnifying-glass', 'magnifier' => 'pinakas::components.icons.magnifying-glass',
            'search' => 'pinakas::components.icons.search',
            default => null,
        };
    }

    public function setDebounceMs(?int $milliseconds): self
    {
        $this->debounceMs = $this->normalizeDebounceMs($milliseconds);

        return $this;
    }

    public function debounceMs(): int
    {
        return $this->debounceMs;
    }

    public function setMinChars(?int $minChars): self
    {
        $this->minChars = $this->normalizeMinChars($minChars);

        return $this;
    }

    public function minChars(): int
    {
        return $this->minChars;
    }

    public function setRounded(?string $rounded): self
    {
        $this->rounded = $this->normalizeRounded((string) $rounded);

        return $this;
    }

    public function rounded(): string
    {
        return $this->rounded;
    }

    public function currentTerm(): string
    {
        return trim((string) request()->query($this->queryName, ''));
    }

    public function searchableAttributes(array $columns): array
    {
        $explicit = array_values(array_unique(array_filter(array_map(
            fn ($column) => ($column->searchable ?? false) ? ($column->attribute ?? null) : null,
            $columns
        ))));

        if (!empty($explicit)) {
            return $explicit;
        }

        return array_values(array_unique(array_filter(array_map(
            fn ($column) => $column->attribute ?? null,
            $columns
        ))));
    }

    public function apply(Builder $query, array $columns): void
    {
        if (! $this->enabled) {
            return;
        }

        $searchTerm = $this->currentTerm();
        $attributes = $this->searchableAttributes($columns);

        if ($searchTerm === '' || empty($attributes)) {
            return;
        }

        $query->where(function ($builder) use ($attributes, $searchTerm) {
            foreach ($attributes as $attribute) {
                $builder->orWhere($attribute, 'like', '%' . $searchTerm . '%');
            }
        });
    }

    private function normalizeText(?string $value, string $fallback): string
    {
        $trimmed = trim((string) $value);

        return $trimmed !== '' ? $trimmed : $fallback;
    }

    private function normalizeNullableText(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $trimmed = trim($value);

        return $trimmed !== '' ? $trimmed : null;
    }

    private function normalizeDebounceMs(?int $value): int
    {
        $ms = (int) ($value ?? 350);

        return $ms >= 0 ? $ms : 350;
    }

    private function normalizeMinChars(?int $value): int
    {
        $min = (int) ($value ?? 3);

        return $min >= 0 ? $min : 3;
    }

    private function normalizeRounded(string $value): string
    {
        $trimmed = trim($value);

        return $trimmed !== '' ? $trimmed : 'rounded-none';
    }
}
