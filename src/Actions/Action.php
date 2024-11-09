<?php

namespace Mimisk13\Pinakas\Actions;

class Action
{
    public $label;
    public $url;
    public $method;
    public $class;
    public $icon;

    public function __construct(array $attributes)
    {
        $this->label = $attributes['label'] ?? '';
        $this->url = $attributes['url'] ?? '#';
        $this->method = $attributes['method'] ?? 'GET';
        $this->class = $attributes['class'] ?? '';
        $this->icon = $attributes['icon'] ?? null;
    }

    public static function make(string $label): self
    {
        return new self($label);
    }

    public function url(string $url): self
    {
        $this->url = $url;
        return $this;
    }

    public function route(string $route): self
    {
        $this->route = $route;
        return $this;
    }

    public function method(string $method): self
    {
        $this->method = $method;
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
}
