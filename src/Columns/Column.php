<?php

namespace Mimisk13\Pinakas\Columns;

class Column
{
    public string $name;
    public string $attribute;
    public bool $sortable = false;

    public function __construct(string $name, string $attribute = '', string $method = 'GET', string $class = 'text-blue-500 hover:underline')
    {
        $this->name = $name;
        $this->attribute = $attribute;
    }

    public static function make(string $name, string $attribute = ''): self
    {
        return new self($name, $attribute);
    }

    public function sortable(): self
    {
        $this->sortable = true;

        return $this;
    }

    public function render()
    {
        // Απόδοση HTML για τη στήλη
    }
}
