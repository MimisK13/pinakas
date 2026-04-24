<?php

namespace Mimisk\Pinakas\Actions;

class Action
{
    public $label;
    public $url;
    public $method;
    public $class;
    public $icon;
    public $route;
    public $routeParameters;
    public $confirm;

    public function __construct(array $attributes)
    {
        $this->label = $attributes['label'] ?? '';
        $this->url = $attributes['url'] ?? '#';
        $this->method = strtoupper((string) ($attributes['method'] ?? 'GET'));
        $this->class = $attributes['class'] ?? '';
        $this->icon = $attributes['icon'] ?? null;
        $this->route = $attributes['route'] ?? null;
        $this->routeParameters = $attributes['routeParameters'] ?? [];
        $this->confirm = $attributes['confirm'] ?? null;
    }

    public static function make(string $label): self
    {
        return new self(['label' => $label]);
    }

    public function url(string $url): self
    {
        $this->url = $url;
        return $this;
    }

    public function route(string $route, array|callable $parameters = []): self
    {
        $this->route = $route;
        $this->routeParameters = $parameters;
        return $this;
    }

    public function method(string $method): self
    {
        $this->method = strtoupper($method);
        return $this;
    }

    public function class(string $class): self
    {
        $this->class = $class;
        return $this;
    }

    public function icon($icon = null): self
    {
        $this->icon = $icon;
        return $this;
    }

    public function confirm(?string $message): self
    {
        $trimmed = trim((string) $message);
        $this->confirm = $trimmed !== '' ? $trimmed : null;

        return $this;
    }

    public function resolveUrl(mixed $row): string
    {
        if (is_string($this->route) && trim($this->route) !== '') {
            $parameters = is_callable($this->routeParameters)
                ? call_user_func($this->routeParameters, $row)
                : $this->routeParameters;

            return route($this->route, $parameters);
        }

        if (is_callable($this->url)) {
            return (string) call_user_func($this->url, $row);
        }

        return (string) $this->url;
    }
}
