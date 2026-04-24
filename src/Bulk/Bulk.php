<?php

namespace Mimisk\Pinakas\Bulk;

class Bulk
{
    private array $actions = [];

    private string $selectedInputName = 'selected_ids';

    public function __construct(array $actions = [], string $selectedInputName = 'selected_ids')
    {
        $this->setActions($actions);
        $this->setSelectedInputName($selectedInputName);
    }

    public function setActions(array $actions): self
    {
        $this->actions = array_values($actions);

        return $this;
    }

    public function actions(): array
    {
        return $this->actions;
    }

    public function hasActions(): bool
    {
        return ! empty($this->actions);
    }

    public function setSelectedInputName(?string $name): self
    {
        $trimmed = trim((string) $name);
        $this->selectedInputName = $trimmed !== '' ? $trimmed : 'selected_ids';

        return $this;
    }

    public function selectedInputName(): string
    {
        return $this->selectedInputName;
    }
}
