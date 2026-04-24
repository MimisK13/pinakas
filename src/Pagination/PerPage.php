<?php

namespace Mimisk\Pinakas\Pagination;

class PerPage
{
    public function __construct(
        private array $options = [10, 25, 50],
        private string $queryName = 'per_page',
        private ?string $label = null,
    ) {
        $this->options = $this->normalize($this->options);
        $this->label = $this->normalizeLabel($this->label);
    }

    public function configure(array $options, string $queryName = 'per_page'): self
    {
        $this->options = $this->normalize($options);
        $this->queryName = $queryName;

        return $this;
    }

    public function queryName(): string
    {
        return $this->queryName;
    }

    public function label(string $label): self
    {
        $this->label = $this->normalizeLabel($label);

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function options(int $defaultPerPage): array
    {
        $options = $this->options;

        if (!in_array($defaultPerPage, $options, true)) {
            $options[] = $defaultPerPage;
            sort($options);
        }

        return $options;
    }

    public function resolve(int $defaultPerPage, ?int $requestedPerPage = null): int
    {
        $allowedOptions = $this->options($defaultPerPage);

        if ($requestedPerPage !== null && in_array($requestedPerPage, $allowedOptions, true)) {
            return $requestedPerPage;
        }

        return $defaultPerPage;
    }

    private function normalize(array $options): array
    {
        $normalized = array_values(array_unique(array_filter(
            array_map(static fn ($option) => (int) $option, $options),
            static fn (int $option) => $option > 0
        )));

        if (empty($normalized)) {
            return [10, 25, 50];
        }

        sort($normalized);

        return $normalized;
    }

    private function normalizeLabel(?string $label): ?string
    {
        if ($label === null) {
            return null;
        }

        $trimmed = trim($label);

        return $trimmed !== '' ? $trimmed : null;
    }
}
