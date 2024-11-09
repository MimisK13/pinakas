<div x-data="{ open: false }" class="relative inline-block text-left">
    <!-- Dropdown Button -->
    <div>
        <button type="button"
                x-on:click="open = !open"
                class="rounded-md px-1 py-1 font-semibold text-gray-500 hover:bg-gray-50 hover:opacity-75 transition duration-300">
            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
                <path d="M10 3a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM10 8.5a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM11.5 15.5a1.5 1.5 0 1 0-3 0 1.5 1.5 0 0 0 3 0Z" />
            </svg>
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
         class="absolute right-0 z-10 mt-2 w-56 origin-top-right rounded-md bg-white shadow-sm ring-1 ring-black ring-opacity-5 focus:outline-none"
         role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1"
    >

            @foreach ($actionGroup as $key => $action)
                <div class="py-1" role="none">
                    @if ($action->method === 'GET')
                        <a href="{{ is_callable($action->url) ? call_user_func($action->url, $row) : $action->url }}" class="{{ $action->class }} group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem" tabindex="-1" id="menu-item-0">
                            <!-- Active: "text-gray-500", Not Active: "" -->
                            @if ($action->icon)
                                {!! $action->icon !!}
                            @endif

                            {{ $action->label }}
                        </a>
                    @elseif ($action->method === 'DELETE')
                        <form action="{{ is_callable($action->url) ? call_user_func($action->url, $row) : $action->url }}"
                              method="POST" class="inline"
                        >
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="group flex items-center px-4 py-2 text-sm text-gray-700"
                                    role="menuitem" tabindex="-1"
                            >
                                {!! $action->icon !!}

                                {{ $action->label }}
                            </button>
                        </form>
                    @endif
                </div>
            @endforeach

    </div>
</div>
