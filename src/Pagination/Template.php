<?php

namespace Mimisk\Pinakas\Pagination;

class Template
{
    public function __construct(
        private string $name = 'default',
    ) {
        $this->name = trim($this->name) !== '' ? $this->name : 'default';
    }

    public function use(string $name): self
    {
        $trimmed = trim($name);
        $this->name = $trimmed !== '' ? $trimmed : 'default';

        return $this;
    }

    public function view(): ?string
    {
        if ($this->name === 'default') {
            return null;
        }

        $builtIn = $this->builtInTemplate($this->name);
        if ($builtIn !== null) {
            return $builtIn;
        }

        return $this->name;
    }

    private function builtInTemplate(string $name): ?string
    {
        return match ($name) {
            'centered-page-numbers' => 'pinakas::pagination.centered-page-numbers',
            default => null,
        };
    }
}
