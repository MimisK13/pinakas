<?php

namespace Mimisk13\Pinakas;

use Illuminate\Database\Eloquent\Model;
use Mimisk13\Pinakas\Actions\ActionGroup;

class Pinakas
{
//    public Model $model;
    protected static ?string $model = null;

    public array $columns = [];

    public array $actions = [];

    public array $bulkActions = [];

    public array $filters = [];

    public function model(string $model)
    {
        self::$model = $model;
        return $this;
    }

    public static function getModel(): ?string
    {
        if (!self::$model) {
            throw new \Exception("Model is not set in Pinakas.");
        }
        return self::$model;
    }

    public function getData()
    {
        if (is_string(self::$model)) {
            $modelInstance = new self::$model;
            return $modelInstance->all();
        }

        throw new \Exception("The model is not defined or is invalid.");
    }

    public function columns(array $columns): self
    {
        $this->columns = $columns;
        return $this;
    }

    public function actions(array $actions): self
    {
        foreach ($actions as $action) {
            if ($action instanceof ActionGroup) {
                $this->actions[] = $action->getActions();
            } else {
                $this->actions[] = $action;
            }
        }

        return $this;
    }

    public function getActions(): array
    {
        return $this->actions;
    }

    public function bulkActions(array $actions): self
    {
        $this->bulkActions = $actions;
        return $this;
    }

    public function getBulkActions(): array
    {
        return $this->bulkActions;
    }

}
