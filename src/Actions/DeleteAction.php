<?php

namespace Mimisk\Pinakas\Actions;

use Mimisk\Pinakas\Support\Icon;

class DeleteAction
{
    public static function make()
    {
        return new Action([
            'label' => 'Delete',
            'url' => function ($row) {
                $modelName = strtolower(class_basename($row));
                return route(strtolower($modelName) . '.destroy', [$modelName => $row->id]);
            },
            'method' => 'DELETE',
            'class' => '',
            'icon' => Icon::delete(),
            'confirm' => 'Are you sure you want to delete this record?',
        ]);
    }
}
