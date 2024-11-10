<?php

namespace Mimisk13\Pinakas;

use ArrayAccess;
use IteratorAggregate;
use Traversable;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Mimisk13\Pinakas\Actions\ActionGroup;

class Pinakas implements ArrayAccess, IteratorAggregate
{
    protected static ?string $model = null;

    public array $columns = [];

    public array $actions = [];

    public array $bulkActions = [];

    public array $filters = [];

    protected LengthAwarePaginator $data; // Αποθήκευση των paginated δεδομένων


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

//        if (is_string(self::$model)) {
//            $modelInstance = new self::$model;
//
//            // Επιστρέφουμε το query instance για να μπορεί να κληθεί η paginate()
//            return $modelInstance->query();
//        }

        throw new \Exception("The model is not defined or is invalid.");
    }

    public function paginate(int $perPage = 15): self
    {
        if (is_string(self::$model)) {
            $modelInstance = new self::$model;
            $this->data = $modelInstance->query()->paginate($perPage);
            return $this;
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




    // Προσθήκη για να επιτρέπει πρόσβαση στα δεδομένα σαν collection
    public function offsetExists($offset): bool
    {
        return isset($this->data[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->data[$offset];
    }

    public function offsetSet($offset, $value): void
    {
        $this->data[$offset] = $value;
    }

    public function offsetUnset($offset): void
    {
        unset($this->data[$offset]);
    }

    public function getIterator(): Traversable
    {
        return $this->data->getIterator();
    }

    // Επιστροφή του paginator links απευθείας από τον $pinakas
    public function links()
    {
        return $this->data->links();
    }

}
