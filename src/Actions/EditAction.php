<?php

namespace Mimisk\Pinakas\Actions;

use Mimisk\Pinakas\Support\Icon;

class EditAction
{
    public static function make()
    {
        return new Action([
            'label' => 'Edit',
            'url' => function ($row) {
                $modelName = strtolower(class_basename($row));
                return route(strtolower($modelName) . '.edit', [$modelName => $row->id]);
            },
            'method' => 'GET',
            'class' => 'text-blue-500 hover:underline',
            'icon' => Icon::edit()
        ]);
    }
}
