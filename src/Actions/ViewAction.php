<?php

namespace Mimisk\Pinakas\Actions;

use Mimisk\Pinakas\Support\Icon;

class ViewAction
{
    public static function make(): Action
    {
        return new Action([
            'label' => 'View',
            'url' => function ($row) {
                $modelName = strtolower(class_basename($row));
                return route(strtolower($modelName) . '.show', [$modelName => $row->id]);
            },
            'method' => 'GET',
            'class' => 'text-blue-500 hover:underline',
            'icon' => Icon::view()
        ]);
    }
}
