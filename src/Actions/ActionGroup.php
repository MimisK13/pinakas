<?php

namespace Mimisk13\Pinakas\Actions;

class ActionGroup
{
    public array $actions;

    public function __construct(array $actions)
    {
        $this->actions = $actions;
    }

    public static function make(array $actions): self
    {
        return new self($actions);
    }

    public function getActions(): array
    {
        return $this->actions;
    }
}
