@php
    $isSearch = ($variant ?? 'table') === 'search';
@endphp

<div class="flex w-full flex-col items-center justify-center py-10 text-center">
    <div class="mb-3 rounded-full bg-gray-100 p-3 text-gray-400 dark:bg-gray-800 dark:text-gray-500">
        @if ($isSearch)
            @include('pinakas::components.icons.search-empty', ['class' => 'size-6'])
        @else
            @include('pinakas::components.icons.table-empty', ['class' => 'size-6'])
        @endif
    </div>

    <h3 class="text-sm font-semibold text-gray-800 dark:text-gray-100">
        {{ $title }}
    </h3>

    @if (!empty($description))
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            {{ $description }}
        </p>
    @endif
</div>
