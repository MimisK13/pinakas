<div x-data="{ open: false }" x-cloak class="relative inline-block text-left">
    <!-- Dropdown Button -->
    <div>
        <button type="button"
                x-on:click="open = !open"
                class="{{ $table->getActionButtonRoundedClass() }} px-1 py-1 font-semibold text-gray-500 hover:bg-gray-50 hover:opacity-75 transition duration-300 dark:text-gray-300 dark:hover:bg-gray-800">
            @include('pinakas::components.icons.ellipsis-vertical')
        </button>
    </div>

    <!-- Dropdown Menu -->
    <div x-show="open"
         x-on:click.outside="open = false"
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="opacity-0 transform scale-95"
         x-transition:enter-end="opacity-100 transform scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="opacity-100 transform scale-100"
         x-transition:leave-end="opacity-0 transform scale-95"
         x-cloak
         class="absolute right-0 z-10 mt-2 w-56 origin-top-right {{ $table->getActionDropdownRoundedClass() }} bg-white shadow-sm ring-1 ring-black ring-opacity-5 focus:outline-none dark:bg-gray-800 dark:ring-white/10"
         role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1"
    >

            @foreach ($actionGroup as $key => $action)
                <div class="py-1" role="none">
                    @include('pinakas::partials.action-item', [
                        'action' => $action,
                        'row' => $row,
                        'class' => 'group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-100 dark:hover:bg-gray-700',
                        'formClass' => 'w-full',
                        'role' => 'menuitem',
                        'tabIndex' => '-1',
                    ])
                </div>
            @endforeach

    </div>
</div>
