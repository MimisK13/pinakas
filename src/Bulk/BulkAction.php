<?php

namespace Mimisk\Pinakas\Bulk;

class BulkAction
{
    private string $label;

    private string $url = '#';

    private string $method = 'POST';

    private string $class = '';

    private ?string $confirm = null;

    private string|null $icon = null;

    private function __construct(string $label)
    {
        $trimmed = trim($label);
        $this->label = $trimmed !== '' ? $trimmed : 'Bulk Action';
    }

    public static function make(string $label): self
    {
        return new self($label);
    }

    public function url(string $url): self
    {
        $trimmed = trim($url);
        $this->url = $trimmed !== '' ? $trimmed : '#';

        return $this;
    }

    public function method(string $method): self
    {
        $normalized = strtoupper(trim($method));
        $this->method = in_array($normalized, ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'], true)
            ? $normalized
            : 'POST';

        return $this;
    }

    public function class(string $class): self
    {
        $this->class = trim($class);

        return $this;
    }

    public function confirm(?string $message): self
    {
        $trimmed = trim((string) $message);
        $this->confirm = $trimmed !== '' ? $trimmed : null;

        return $this;
    }

    public function icon(?string $icon): self
    {
        $trimmed = trim((string) $icon);
        $this->icon = $trimmed !== '' ? $trimmed : null;

        return $this;
    }

    public function label(): string
    {
        return $this->label;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function getConfirm(): ?string
    {
        return $this->confirm;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }
}
