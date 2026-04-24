<?php

namespace Mimisk\Pinakas\States;

class EmptyState
{
    public function __construct(
        private string $title,
        private ?string $description = null,
    ) {
        $this->title = $this->normalizeText($this->title, 'No records found');
        $this->description = $this->normalizeNullableText($this->description);
    }

    public function set(string $title, ?string $description = null): self
    {
        $this->title = $this->normalizeText($title, 'No records found');
        $this->description = $this->normalizeNullableText($description);

        return $this;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function description(): ?string
    {
        return $this->description;
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
}
