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
                return route(strtolower($modelName) . '.delete', [$modelName => $row->id]);
            },
            'method' => 'DELETE',
            'class' => 'text-red-500 hover:underline',
            'icon' => Icon::delete()
        ]);
    }
}
