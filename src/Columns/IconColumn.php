<?php

namespace Mimisk\Pinakas\Columns;

class IconColumn
{
    protected $name;
    protected $isBoolean = false;

    public static function make($name)
    {
        $column = new self();
        $column->name = $name;
        return $column;
    }

    public function boolean()
    {
        $this->isBoolean = true;
        return $this;
    }

    public function render()
    {
        $icon = $this->isBoolean ? '✓' : '✗';
        return "<th>{$this->name} ({$icon})</th>";
    }
}
