@php
    $itemClass = trim(($action->class ?? '') . ' ' . ($class ?? ''));
@endphp

@if ($action->method === 'GET')
    <a
        href="{{ is_callable($action->url) ? call_user_func($action->url, $row) : $action->url }}"
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
    <form action="{{ is_callable($action->url) ? call_user_func($action->url, $row) : $action->url }}" method="POST" class="{{ $formClass ?? 'inline' }}">
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
