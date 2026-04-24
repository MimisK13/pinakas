<div class="flex justify-end">
    <form method="GET" class="flex flex-col items-end gap-1">
        @foreach (request()->except([$table->getPerPageQueryName(), $rows->getPageName()]) as $key => $value)
            @if (is_array($value))
                @foreach ($value as $item)
                    <input type="hidden" name="{{ $key }}[]" value="{{ $item }}">
                @endforeach
            @else
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endif
        @endforeach

        @if ($table->getPerPageLabel())
            <label for="pinakas-per-page" class="block text-sm/6 font-medium text-gray-900 dark:text-gray-100">{{ $table->getPerPageLabel() }}</label>
        @endif

        <el-select
            id="pinakas-per-page"
            name="{{ $table->getPerPageQueryName() }}"
            value="{{ $table->currentPerPage() }}"
            class="block"
            onchange="this.closest('form')?.submit()"
        >
            <button type="button" class="grid h-9 w-full min-w-[84px] cursor-default grid-cols-1 {{ $table->getPaginationDropdownRoundedClass() }} bg-white py-1.5 pl-3 pr-2 text-left text-gray-800 outline outline-1 -outline-offset-1 outline-gray-300 focus-visible:outline focus-visible:outline-2 focus-visible:-outline-offset-2 focus-visible:outline-gray-400 sm:text-sm/6 dark:bg-gray-800 dark:text-gray-100 dark:outline-gray-600 dark:focus-visible:outline-gray-500">
                <el-selectedcontent class="col-start-1 row-start-1 truncate pr-6">
                    {{ $table->currentPerPage() }}
                </el-selectedcontent>

                @include('pinakas::components.icons.chevrons-up-down', ['class' => 'col-start-1 row-start-1 size-5 self-center justify-self-end text-gray-400 sm:size-4 dark:text-gray-300'])
            </button>

            <el-options anchor="bottom start" popover class="m-0 max-h-60 w-[var(--button-width)] overflow-auto {{ $table->getPaginationDropdownRoundedClass() }} bg-white p-0 py-1 text-base shadow-lg outline outline-1 outline-black/5 [--anchor-gap:theme(spacing.1)] data-[closed]:data-[leave]:opacity-0 data-[leave]:transition data-[leave]:duration-100 data-[leave]:ease-in data-[leave]:[transition-behavior:allow-discrete] sm:text-sm dark:bg-gray-800 dark:outline-white/10">
                @foreach ($table->getPerPageOptions() as $option)
                    <el-option value="{{ $option }}" class="group/option relative cursor-default select-none py-2 pl-8 pr-4 text-gray-800 focus:bg-gray-100 focus:text-gray-900 focus:outline-none [&:not([hidden])]:block dark:text-gray-100 dark:focus:bg-gray-700 dark:focus:text-white">
                        <span class="block truncate font-normal group-aria-selected/option:font-semibold">{{ $option }}</span>
                        <span class="absolute inset-y-0 left-0 flex items-center pl-1.5 text-gray-500 group-focus/option:text-gray-700 group-[:not([aria-selected='true'])]/option:hidden [el-selectedcontent_&]:hidden dark:text-gray-300 dark:group-focus/option:text-white">
                            @include('pinakas::components.icons.check', ['class' => 'size-5'])
                        </span>
                    </el-option>
                @endforeach
            </el-options>
        </el-select>
    </form>
</div>
