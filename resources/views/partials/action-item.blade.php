@php
    $isDestructive = ($action->method ?? null) === 'DELETE';
    $baseClass = $isDestructive
        ? 'group flex w-full items-center justify-start px-4 py-2 text-sm text-red-600 hover:bg-red-50 hover:text-red-700 focus:bg-red-50 focus:text-red-700 focus:outline-none dark:text-red-400 dark:hover:bg-red-950/40 dark:hover:text-red-300 dark:focus:bg-red-950/40 dark:focus:text-red-300'
        : ($class ?? '');
    $itemClass = trim(($isDestructive ? '' : ($action->class ?? '')) . ' ' . $baseClass);
    $actionUrl = method_exists($action, 'resolveUrl')
        ? $action->resolveUrl($row)
        : (is_callable($action->url) ? call_user_func($action->url, $row) : $action->url);
@endphp

@if ($action->method === 'GET')
    <a
        href="{{ $actionUrl }}"
        class="{{ $itemClass }}"
        @if (!empty($role)) role="{{ $role }}" @endif
        @if (!empty($tabIndex)) tabindex="{{ $tabIndex }}" @endif
    >
        @if (!empty($action->icon))
            {!! $action->icon !!}
        @endif
        {{ $action->label }}
    </a>
@elseif ($action->method === 'DELETE')
    <form
        action="{{ $actionUrl }}"
        method="POST"
        class="{{ $formClass ?? 'w-full' }}"
        @if (!empty($action->confirm))
            x-on:submit.prevent.stop="submitRowAction($el, @js($action->confirm))"
        @endif
    >
        @csrf
        @method('DELETE')
        <button
            type="submit"
            class="{{ $itemClass }}"
            @if (!empty($role)) role="{{ $role }}" @endif
            @if (!empty($tabIndex)) tabindex="{{ $tabIndex }}" @endif
        >
            @if (!empty($action->icon))
                {!! $action->icon !!}
            @endif
            {{ $action->label }}
        </button>
    </form>
@endif
