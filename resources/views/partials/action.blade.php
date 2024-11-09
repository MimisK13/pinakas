@php
//    dd($table->getActions());
@endphp

@if ($action->method === 'GET')
    <a href="{{ is_callable($action->url) ? call_user_func($action->url, $row) : $action->url }}" class="{{ $action->class }}">
        {{ $action->label }}
    </a>
@elseif ($action->method === 'DELETE')
    <form action="{{ is_callable($action->url) ? call_user_func($action->url, $row) : $action->url }}" method="POST" class="inline">
        @csrf
        @method('DELETE')
        <button type="submit" class="{{ $action->class }}">
            {{ $action->label }}
        </button>
    </form>
@endif
