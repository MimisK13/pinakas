<div class="flex items-end">
    <form method="GET" class="flex flex-col gap-1">
        @if ($table->getSearchLabel())
            <label for="pinakas-search" class="text-sm/6 font-medium text-gray-900 dark:text-gray-100">{{ $table->getSearchLabel() }}</label>
        @endif

        @foreach (request()->except([$table->getSearchQueryName(), $rows->getPageName()]) as $key => $value)
            @if (is_array($value))
                @foreach ($value as $item)
                    <input type="hidden" name="{{ $key }}[]" value="{{ $item }}">
                @endforeach
            @else
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endif
        @endforeach

        @php($searchIconView = $table->getSearchIconView())

        <div class="relative">
            @if ($searchIconView)
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 dark:text-gray-500">
                    @include($searchIconView, ['class' => 'size-4'])
                </span>
            @endif

        <input
            id="pinakas-search"
            type="search"
            name="{{ $table->getSearchQueryName() }}"
            value="{{ $table->currentSearchTerm() }}"
            placeholder="{{ $table->getSearchPlaceholder() }}"
            class="h-9 w-full min-w-[320px] {{ $table->getSearchRoundedClass() }} border border-gray-300 bg-white text-sm text-gray-900 outline-none transition focus:border-gray-400 focus:ring-1 focus:ring-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100 dark:placeholder-gray-400 dark:focus:border-gray-500 dark:focus:ring-gray-600 {{ $searchIconView ? 'pl-9 pr-3' : 'px-3' }}"
            oninput="const el=this; const min={{ $table->getSearchMinChars() }}; const len=el.value.trim().length; clearTimeout(el._searchTimer); if (len !== 0 && len < min) { return; } el._searchTimer=setTimeout(function(){ if (el.form?.requestSubmit) { el.form.requestSubmit(); } else { el.form.submit(); } }, {{ $table->getSearchDebounceMs() }});"
        >
        </div>
    </form>
</div>
