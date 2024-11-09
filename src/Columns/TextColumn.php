<?php

namespace Mimisk13\Pinakas\Columns;

class TextColumn
{
    protected $name;
    protected $isSortable = false;

    public static function make($name)
    {
        $column = new static();
        $column->name = $name;
        return $column;
    }

    public function sortable()
    {
        $this->isSortable = true;
        return $this;
    }

    public function render()
    {
        return "<th>{$this->name}</th>";
    }
}
