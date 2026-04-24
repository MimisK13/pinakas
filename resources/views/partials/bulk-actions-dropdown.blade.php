<div x-data="{ open: false }" x-cloak class="relative inline-block text-left">
    <div>
        <button
            type="button"
            x-on:click="open = !open"
            x-bind:disabled="selected.length === 0"
            class="inline-flex h-9 items-center {{ $table->getActionButtonRoundedClass() }} border border-gray-300 bg-white px-3 font-semibold text-gray-500 transition duration-300 hover:bg-gray-50 hover:opacity-75 disabled:cursor-not-allowed disabled:opacity-40 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700"
            aria-haspopup="menu"
            x-bind:aria-expanded="open ? 'true' : 'false'"
        >
            @include('pinakas::components.icons.ellipsis-vertical', ['class' => 'h-5 w-5'])
        </button>
    </div>

    <div
        x-show="open"
        x-on:click.outside="open = false"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 transform scale-95"
        x-transition:enter-end="opacity-100 transform scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 transform scale-100"
        x-transition:leave-end="opacity-0 transform scale-95"
        x-cloak
        class="absolute right-0 z-10 mt-2 w-64 origin-top-right {{ $table->getActionDropdownRoundedClass() }} bg-white shadow-sm ring-1 ring-black ring-opacity-5 focus:outline-none dark:bg-gray-800 dark:ring-white/10"
        role="menu"
        aria-orientation="vertical"
        tabindex="-1"
    >
        @foreach ($table->getBulkActions() as $action)
            <div class="py-1" role="none">
                <button
                    type="button"
                    x-on:click="submitBulkAction(@js($table->getBulkActionUrl($action)), @js($table->getBulkActionMethod($action)), @js($table->getBulkActionConfirm($action))); open = false"
                    class="group flex w-full items-center px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-100 dark:hover:bg-gray-700"
                    role="menuitem"
                    tabindex="-1"
                >
                    @if ($table->getBulkActionIcon($action))
                        {!! $table->getBulkActionIcon($action) !!}
                    @endif
                    {{ $table->getBulkActionLabel($action) }}
                </button>
            </div>
        @endforeach
    </div>
</div>
