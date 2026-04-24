<?php

namespace Mimisk\Pinakas\Sort;

use Illuminate\Database\Eloquent\Builder;

class Sort
{
    public function __construct(
        private bool $enabled = false,
        private string $queryName = 'sort',
        private string $directionQueryName = 'direction',
        private string $defaultDirection = 'asc',
        private string $iconPosition = 'right',
    ) {
        $this->queryName = $this->normalizeQueryName($this->queryName, 'sort');
        $this->directionQueryName = $this->normalizeQueryName($this->directionQueryName, 'direction');
        $this->defaultDirection = $this->normalizeDirection($this->defaultDirection, 'asc');
        $this->iconPosition = $this->normalizeIconPosition($this->iconPosition);
    }

    public function enable(string $queryName = 'sort', string $directionQueryName = 'direction'): self
    {
        $this->enabled = true;
        $this->queryName = $this->normalizeQueryName($queryName, 'sort');
        $this->directionQueryName = $this->normalizeQueryName($directionQueryName, 'direction');

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

    public function directionQueryName(): string
    {
        return $this->directionQueryName;
    }

    public function defaultDirection(): string
    {
        return $this->defaultDirection;
    }

    public function setIconPosition(?string $position): self
    {
        $this->iconPosition = $this->normalizeIconPosition((string) $position);

        return $this;
    }

    public function iconPosition(): string
    {
        return $this->iconPosition;
    }

    public function sortableAttributes(array $columns): array
    {
        return array_values(array_unique(array_filter(array_map(
            fn ($column) => (($column->sortable ?? false) && !empty($column->attribute ?? null))
                ? $column->attribute
                : null,
            $columns
        ))));
    }

    public function isSortableColumn($column): bool
    {
        return (bool) ($column->sortable ?? false) && trim((string) ($column->attribute ?? '')) !== '';
    }

    public function currentSort(array $columns): ?string
    {
        if (! $this->enabled) {
            return null;
        }

        $requested = trim((string) request()->query($this->queryName, ''));
        if ($requested === '') {
            return null;
        }

        return in_array($requested, $this->sortableAttributes($columns), true) ? $requested : null;
    }

    public function currentDirection(array $columns): string
    {
        $currentSort = $this->currentSort($columns);
        if ($currentSort === null) {
            return $this->defaultDirection;
        }

        return $this->normalizeDirection(
            (string) request()->query($this->directionQueryName, $this->defaultDirection),
            $this->defaultDirection
        );
    }

    public function nextDirection(string $attribute, array $columns): string
    {
        $currentSort = $this->currentSort($columns);
        $currentDirection = $this->currentDirection($columns);

        if ($currentSort !== $attribute) {
            return $this->defaultDirection;
        }

        return $currentDirection === 'asc' ? 'desc' : 'asc';
    }

    public function indicator(string $attribute, array $columns): ?string
    {
        if ($this->currentSort($columns) !== $attribute) {
            return null;
        }

        return $this->currentDirection($columns);
    }

    public function queryFor(string $attribute, array $columns, ?string $pageName = null): array
    {
        $query = request()->query();
        $query[$this->queryName] = $attribute;
        $query[$this->directionQueryName] = $this->nextDirection($attribute, $columns);

        if (is_string($pageName) && trim($pageName) !== '') {
            $query[$pageName] = 1;
        }

        return $query;
    }

    public function apply(Builder $query, array $columns): void
    {
        $sort = $this->currentSort($columns);
        if ($sort === null) {
            return;
        }

        $direction = $this->currentDirection($columns);
        $query->orderBy($sort, $direction);
    }

    private function normalizeQueryName(string $value, string $fallback): string
    {
        $trimmed = trim($value);

        return $trimmed !== '' ? $trimmed : $fallback;
    }

    private function normalizeDirection(string $value, string $fallback): string
    {
        $normalized = strtolower(trim($value));

        return in_array($normalized, ['asc', 'desc'], true) ? $normalized : $fallback;
    }

    private function normalizeIconPosition(string $value): string
    {
        $normalized = strtolower(trim($value));

        return in_array($normalized, ['left', 'right'], true) ? $normalized : 'right';
    }
}
