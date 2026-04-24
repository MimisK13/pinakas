@php
    $itemClass = trim(($action->class ?? '') . ' ' . ($class ?? ''));
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
        class="{{ $formClass ?? 'inline' }}"
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
