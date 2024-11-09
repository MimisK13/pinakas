<?php

namespace Mimisk13\Pinakas\Actions;

use Mimisk13\Pinakas\Pinakas;

class ViewAction
{
    public static function make(): Action
    {
        $modelName = strtolower(class_basename(Pinakas::getModel()));

        return new Action([
            'label' => 'View',
            'url' => function ($row) use ($modelName) {
                return route(strtolower($modelName) . '.show', [$modelName => $row->id]);
            },
            'method' => 'GET',
            'class' => 'text-blue-500 hover:underline',
            'icon' => '<svg class="mr-3 h-5 w-5 text-gray-400" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 5c-5 0-9 4-9 7s4 7 9 7 9-4 9-7-4-7-9-7zm0 12c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5z"/><circle cx="12" cy="12" r="2"/></svg>'
        ]);
    }
}
