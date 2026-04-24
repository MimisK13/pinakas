<?php

namespace Mimisk\Pinakas\Pagination;

class Paginate
{
    public function __construct(
        private bool $enabled = false,
        private int $perPage = 15,
        private string $pageName = 'page',
    ) {
        $this->perPage = max(1, $this->perPage);
        $this->pageName = trim($this->pageName) !== '' ? $this->pageName : 'page';
    }

    public static function enabled(int $perPage = 15, string $pageName = 'page'): self
    {
        return new self(true, max(1, $perPage), $pageName);
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function perPage(): int
    {
        return $this->perPage;
    }

    public function pageName(): string
    {
        return $this->pageName;
    }
}
