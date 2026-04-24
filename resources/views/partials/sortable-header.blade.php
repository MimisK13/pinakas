@php($query = $table->getColumnSortQuery($column))
@php($indicator = $table->getColumnSortIndicator($column))
@php($url = request()->url() . '?' . http_build_query($query))
@php($iconPosition = $table->getSortIconPosition())

<a
    href="{{ $url }}"
    class="group relative block w-full pr-5 transition hover:text-gray-900 dark:hover:text-gray-100"
>
    @if ($iconPosition === 'left')
        <span class="absolute left-0 top-1/2 inline-flex -translate-y-1/2 text-gray-400 transition group-hover:text-gray-600 dark:text-gray-500 dark:group-hover:text-gray-300">
            @if ($indicator === 'asc')
                @include('pinakas::components.icons.chevron-up', ['class' => 'size-4'])
            @elseif ($indicator === 'desc')
                @include('pinakas::components.icons.chevron-down', ['class' => 'size-4'])
            @else
                @include('pinakas::components.icons.chevron-up-down', ['class' => 'size-4'])
            @endif
        </span>
    @endif

    <span class="block">{{ $column->name }}</span>

    @if ($iconPosition === 'right')
        <span class="absolute right-0 top-1/2 inline-flex -translate-y-1/2 text-gray-400 transition group-hover:text-gray-600 dark:text-gray-500 dark:group-hover:text-gray-300">
            @if ($indicator === 'asc')
                @include('pinakas::components.icons.chevron-up', ['class' => 'size-4'])
            @elseif ($indicator === 'desc')
                @include('pinakas::components.icons.chevron-down', ['class' => 'size-4'])
            @else
                @include('pinakas::components.icons.chevron-up-down', ['class' => 'size-4'])
            @endif
        </span>
    @endif
</a>
