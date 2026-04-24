<?php

namespace Mimisk\Pinakas\UI;

class UI
{
    private const COLOR_MAP = [
        'amber-600' => '#d97706',
        'amber-500' => '#f59e0b',
        'indigo-600' => '#4f46e5',
        'indigo-500' => '#6366f1',
        'blue-600' => '#2563eb',
        'emerald-600' => '#059669',
        'rose-600' => '#e11d48',
        'gray-700' => '#374151',
    ];

    public function __construct(
        private string $accentColor = 'amber-600',
        private bool $tableBordered = false,
        private string $tableRounded = 'rounded-xs',
        private bool $tableStriped = false,
        private bool $tableHoverable = true,
        private string $paginationDropdownRounded = 'rounded-none',
        private string $actionButtonRounded = 'rounded-none',
        private string $actionDropdownRounded = 'rounded-none',
    ) {
        $this->accentColor = $this->normalizeAccentColor($this->accentColor);
        $this->tableRounded = $this->normalizeRounded($this->tableRounded);
        $this->paginationDropdownRounded = $this->normalizeRounded($this->paginationDropdownRounded, 'rounded-none');
        $this->actionButtonRounded = $this->normalizeRounded($this->actionButtonRounded, 'rounded-none');
        $this->actionDropdownRounded = $this->normalizeRounded($this->actionDropdownRounded, 'rounded-none');
    }

    public function setAccentColor(?string $value): self
    {
        $this->accentColor = $this->normalizeAccentColor((string) $value);

        return $this;
    }

    public function accentColor(): string
    {
        return $this->accentColor;
    }

    public function accentCssColor(): string
    {
        $value = $this->accentColor;

        if (isset(self::COLOR_MAP[$value])) {
            return self::COLOR_MAP[$value];
        }

        if (preg_match('/^(#|rgb\\(|hsl\\(|oklch\\()/i', $value) === 1) {
            return $value;
        }

        return self::COLOR_MAP['amber-600'];
    }

    public function setTableBordered(bool $value = true): self
    {
        $this->tableBordered = $value;

        return $this;
    }

    public function tableBordered(): bool
    {
        return $this->tableBordered;
    }

    public function setTableRounded(?string $value): self
    {
        $this->tableRounded = $this->normalizeRounded((string) $value);

        return $this;
    }

    public function tableRounded(): string
    {
        return $this->tableRounded;
    }

    public function setTableStriped(bool $value = true): self
    {
        $this->tableStriped = $value;

        return $this;
    }

    public function tableStriped(): bool
    {
        return $this->tableStriped;
    }

    public function setTableHoverable(bool $value = true): self
    {
        $this->tableHoverable = $value;

        return $this;
    }

    public function tableHoverable(): bool
    {
        return $this->tableHoverable;
    }

    public function setPaginationDropdownRounded(?string $value): self
    {
        $this->paginationDropdownRounded = $this->normalizeRounded((string) $value, 'rounded-none');

        return $this;
    }

    public function paginationDropdownRounded(): string
    {
        return $this->paginationDropdownRounded;
    }

    public function setActionButtonRounded(?string $value): self
    {
        $this->actionButtonRounded = $this->normalizeRounded((string) $value, 'rounded-none');

        return $this;
    }

    public function actionButtonRounded(): string
    {
        return $this->actionButtonRounded;
    }

    public function setActionDropdownRounded(?string $value): self
    {
        $this->actionDropdownRounded = $this->normalizeRounded((string) $value, 'rounded-none');

        return $this;
    }

    public function actionDropdownRounded(): string
    {
        return $this->actionDropdownRounded;
    }

    private function normalizeAccentColor(string $value): string
    {
        $trimmed = trim(strtolower($value));

        return $trimmed !== '' ? $trimmed : 'amber-600';
    }

    private function normalizeRounded(string $value, string $fallback = 'rounded-xs'): string
    {
        $trimmed = trim($value);

        return $trimmed !== '' ? $trimmed : $fallback;
    }
}
