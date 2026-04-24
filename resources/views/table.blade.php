        @php($rows = $table->getData())
        @php($columnSpan = count($table->columns) + (!empty($table->getBulkActions()) ? 1 : 0) + (!empty($table->getActions()) ? 1 : 0))
        @php($bulkRowsSource = $table->hasPagination($rows) ? $rows->items() : $rows)
        @php($bulkRowIds = collect($bulkRowsSource)->map(fn ($row) => $table->getBulkRowId($row))->filter()->values()->all())
        @php($bulkDialogId = 'pinakas-bulk-confirm-dialog')
        @php($isBordered = $table->isBordered())

        <div
            x-data="{
                loading: false,
                selected: [],
                rowIds: @js($bulkRowIds),
                confirmMessage: '',
                pendingAction: null,
                submitBulkAction(url, method, confirmMessage) {
                    if (!url || this.selected.length === 0) return;

                    if (confirmMessage) {
                        this.pendingAction = { url, method };
                        this.confirmMessage = confirmMessage;
                        this.$refs.bulkConfirmDialog?.showModal();
                        return;
                    }

                    this.executeBulkAction(url, method);
                },
                executeBulkAction(url, method) {
                    if (!url || this.selected.length === 0) return;

                    const normalized = (method || 'POST').toUpperCase();
                    const form = this.$refs.bulkForm;
                    if (!form) return;

                    form.setAttribute('action', url);
                    this.$refs.bulkMethod.value = ['PUT', 'PATCH', 'DELETE'].includes(normalized) ? normalized : 'POST';

                    if (form.requestSubmit) {
                        form.requestSubmit();
                    } else {
                        form.submit();
                    }
                },
                confirmBulkAction() {
                    if (!this.pendingAction) return;
                    this.$refs.bulkConfirmDialog?.close();
                    this.executeBulkAction(this.pendingAction.url, this.pendingAction.method);
                    this.pendingAction = null;
                    this.confirmMessage = '';
                },
                cancelBulkAction() {
                    this.$refs.bulkConfirmDialog?.close();
                    this.pendingAction = null;
                    this.confirmMessage = '';
                }
            }"
            x-on:submit="loading = true"
            x-on:click="if ($event.target.closest('a[href]')) { loading = true }"
            x-on:keydown.escape.window="cancelBulkAction()"
            class="relative rounded-sm shadow-xs"
            style="--pinakas-accent: {{ $table->getUiAccentColor() }};"
        > <!--- overflow-x-auto --->
            <div>
                @if ($table->hasPagination($rows) || $table->hasSearch() || $table->hasBulkActions())
                    <div class="mb-3 flex items-end gap-3">
                        @if ($table->hasPagination($rows))
                            @include('pinakas::partials.per-page-dropdown', ['table' => $table, 'rows' => $rows])
                        @endif

                        @if ($table->hasSearch())
                            @include('pinakas::partials.search-input', ['table' => $table, 'rows' => $rows])
                        @endif

                        @if ($table->hasBulkActions())
                            <div class="ml-auto">
                                @include('pinakas::partials.bulk-actions-dropdown', ['table' => $table])
                            </div>
                        @endif
                    </div>
                @endif

            <!-- Bulk Submit Form -->
            @if ($table->hasBulkActions())
                <form x-ref="bulkForm" method="POST" class="hidden">
                    @csrf
                    <input x-ref="bulkMethod" type="hidden" name="_method" value="POST">
                    <template x-for="id in selected" :key="id">
                        <input type="hidden" name="{{ $table->getBulkSelectedInputName() }}[]" :value="id">
                    </template>
                </form>
            @endif

            <div class="relative">
                <div
                    x-show="loading"
                    x-cloak
                    class="absolute inset-0 z-20 flex items-center justify-center rounded-sm bg-white/60 dark:bg-gray-900/60"
                >
                    <div class="h-8 w-8 animate-spin rounded-full border-2 border-gray-300 border-t-[var(--pinakas-accent)] dark:border-gray-600 dark:border-t-[var(--pinakas-accent)]"></div>
                </div>

                <div :class="{ 'opacity-50 pointer-events-none': loading }" class="transition-opacity duration-200">
                    <table class="min-w-full {{ $table->getTableRoundedClass() }} {{ $isBordered ? 'border border-gray-200 dark:border-gray-700' : '' }}">
                        <thead class="bg-gray-50 {{ $isBordered ? 'border-b border-gray-200 dark:border-gray-700' : '' }} dark:bg-gray-800/60">
                            <tr>
                                @if ($table->hasBulkActions())
                                    <th scope="col" class="w-1 whitespace-nowrap px-4 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider {{ $isBordered ? 'border-r border-gray-200 dark:border-gray-700' : '' }} dark:bg-gray-800/60 dark:text-gray-200">
                                        <div class="flex justify-center">
                                            @include('pinakas::components.forms.checkbox', [
                                                'checked' => 'rowIds.length > 0 && selected.length === rowIds.length',
                                                'indeterminate' => 'selected.length > 0 && selected.length < rowIds.length',
                                                'change' => 'selected = $event.target.checked ? [...rowIds] : []',
                                            ])
                                        </div>
                                    </th>
                                @endif

                                @foreach ($table->columns as $column)
                                    <th scope="col" class="px-4 py-3 text-xs font-bold text-gray-700 uppercase tracking-wider {{ $isBordered ? 'border-r border-gray-200 dark:border-gray-700' : '' }} dark:bg-gray-800/60 dark:text-gray-200 {{ $table->getColumnHeaderAlignClass($column) }}">
                                        @if ($table->isSortableColumn($column))
                                            @include('pinakas::partials.sortable-header', ['table' => $table, 'column' => $column])
                                        @else
                                            {{ $column->name }}
                                        @endif
                                    </th>
                                @endforeach

                                @if (!empty($table->getActions()))
                                    <th scope="col" class="w-1 whitespace-nowrap px-4 py-3 text-right text-xs font-bold uppercase tracking-wider text-gray-700 {{ $isBordered ? 'border-r border-gray-200 dark:border-gray-700' : '' }} dark:bg-gray-800/60 dark:text-gray-200"></th>
                                @endif
                            </tr>
                        </thead>

                        <tbody class="bg-white dark:bg-gray-900">
                            @if ($table->hasRows($rows))
                                @foreach ($rows as $row)
                                    <tr class="transition duration-200 {{ $table->isHoverable() ? 'hover:bg-gray-100 dark:hover:bg-gray-800/70' : '' }} {{ $table->isStriped() && $loop->even ? 'bg-gray-50/70 dark:bg-gray-800/30' : '' }}">
                                        @if ($table->hasBulkActions())
                                            <td class="w-1 whitespace-nowrap px-4 py-2 text-center text-sm text-gray-700 {{ $isBordered ? 'border-r border-b border-gray-200 dark:border-gray-700' : '' }} dark:text-gray-200">
                                                @php($bulkRowId = $table->getBulkRowId($row))
                                                @if ($bulkRowId !== null)
                                                    <div class="flex justify-center">
                                                        @include('pinakas::components.forms.checkbox', [
                                                            'model' => 'selected',
                                                            'value' => $bulkRowId,
                                                        ])
                                                    </div>
                                                @endif
                                            </td>
                                        @endif

                                        @foreach ($table->columns as $column)
                                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-700 {{ $isBordered ? 'border-r border-b border-gray-200 dark:border-gray-700' : '' }} dark:text-gray-200 {{ $table->getColumnCellAlignClass($column) }}">
                                                @php($columnValue = $table->renderColumnValue($column, $row))
                                                @if ($columnValue instanceof \Illuminate\Contracts\Support\Htmlable)
                                                    {!! $columnValue->toHtml() !!}
                                                @else
                                                    {{ $columnValue }}
                                                @endif
                                            </td>
                                        @endforeach




                                <!--- ACTIONS --->


{{--                        @foreach ($table->getActions() as $action)--}}
{{--                            @if (is_array($action))--}}
{{--                                @dd('einai array opote einai action group')--}}
{{--                            @endif--}}

{{--                            @if(! is_array($action))--}}
{{--                                @dd('den einai array opote einai single action')--}}
{{--                            @endif--}}
{{--                        @endforeach--}}




                                        @if (!empty($table->getActions()))
                                            <td class="w-1 whitespace-nowrap px-4 py-2 text-right text-sm text-gray-700 {{ $isBordered ? 'border-r border-b border-gray-200 dark:border-gray-700' : '' }} dark:text-gray-200">
                                                @foreach ($table->getActions() as $action)
                                                    @if (is_array($action))
                                                        <div class="flex space-x-2 justify-end">
                                                            @include('pinakas::partials.action-group', ['actionGroup' => $action, 'table' => $table])
                                                        </div>
                                                    @endif
{{--                                        @else--}}
{{--                                            @include('pinakas::partials.action', ['action' => $action])--}}
{{--                                            @include('pinakas::partials.action-group', ['actionGroup' => $action])--}}
{{--                                        @endif--}}
                                                @endforeach
                                            </td>
                                        @endif

                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="{{ max(1, $columnSpan) }}" class="{{ $isBordered ? 'border-b border-gray-200 dark:border-gray-700' : '' }}">
                                        @if ($table->isSearchEmptyState($rows))
                                            @include('pinakas::partials.empty-state', [
                                                'variant' => 'search',
                                                'title' => $table->getSearchEmptyStateTitle(),
                                                'description' => $table->getSearchEmptyStateDescription(),
                                            ])
                                        @else
                                            @include('pinakas::partials.empty-state', [
                                                'variant' => 'table',
                                                'title' => $table->getEmptyStateTitle(),
                                                'description' => $table->getEmptyStateDescription(),
                                            ])
                                        @endif
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            @if ($table->hasPagination($rows))
                <div class="py-3">
                    @php($paginationView = $table->getPaginationTemplateView())
                    @if ($paginationView)
                        {{ $rows->appends(request()->query())->links($paginationView, ['pinakasAccentColor' => $table->getUiAccentColor()]) }}
                    @else
                        {{ $rows->appends(request()->query())->links() }}
                    @endif
                </div>
            @endif
        </div>

        <el-dialog>
            <dialog
                x-ref="bulkConfirmDialog"
                x-cloak
                id="{{ $bulkDialogId }}"
                aria-labelledby="pinakas-bulk-confirm-title"
                class="fixed inset-0 m-0 size-auto max-h-none max-w-none overflow-y-auto bg-transparent p-0 backdrop:bg-transparent"
            >
                <el-dialog-backdrop class="fixed inset-0 bg-gray-500/75 backdrop-blur-sm transition-opacity data-[closed]:opacity-0 data-[enter]:duration-300 data-[leave]:duration-200 data-[enter]:ease-out data-[leave]:ease-in dark:bg-gray-900/50"></el-dialog-backdrop>

                <div tabindex="0" class="flex min-h-full items-end justify-center p-4 text-center focus:outline focus:outline-0 sm:items-center sm:p-0">
                    <el-dialog-panel class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all data-[closed]:translate-y-4 data-[closed]:opacity-0 data-[enter]:duration-300 data-[leave]:duration-200 data-[enter]:ease-out data-[leave]:ease-in sm:my-8 sm:w-full sm:max-w-lg data-[closed]:sm:translate-y-0 data-[closed]:sm:scale-95 dark:bg-gray-800 dark:outline dark:outline-1 dark:-outline-offset-1 dark:outline-white/10">
                        <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4 dark:bg-gray-800">
                            <div class="sm:flex sm:items-start">
                                <div class="mx-auto flex size-12 shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:size-10 dark:bg-red-500/10">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true" class="size-6 text-red-600 dark:text-red-400">
                                        <path d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                                <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                    <h3 id="pinakas-bulk-confirm-title" class="text-base font-semibold text-gray-900 dark:text-white">Confirm Action</h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500 dark:text-gray-400" x-text="confirmMessage"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 dark:bg-gray-700/25">
                            <button
                                type="button"
                                command="close"
                                commandfor="{{ $bulkDialogId }}"
                                x-on:click="confirmBulkAction()"
                                class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto dark:bg-red-500 dark:shadow-none dark:hover:bg-red-400"
                            >
                                Confirm
                            </button>
                            <button
                                type="button"
                                command="close"
                                commandfor="{{ $bulkDialogId }}"
                                x-on:click="cancelBulkAction()"
                                class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto dark:bg-white/10 dark:text-white dark:shadow-none dark:ring-white/5 dark:hover:bg-white/20"
                            >
                                Cancel
                            </button>
                        </div>
                    </el-dialog-panel>
                </div>
            </dialog>
        </el-dialog>


{{--<div class="rounded-lg shadow-sm overflow-hidden">--}}
{{--    <table class="min-w-full divide-y divide-gray-200">--}}
{{--        <thead class="bg-gray-50">--}}
{{--        <tr>--}}
{{--            @foreach ($table->columns as $column)--}}
{{--                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">--}}
{{--                    {{ $column->name }}--}}
{{--                </th>--}}
{{--            @endforeach--}}
{{--            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>--}}
{{--        </tr>--}}
{{--        </thead>--}}
{{--        <tbody class="bg-white divide-y divide-gray-200">--}}
{{--        @foreach ($data as $row)--}}
{{--            <tr>--}}
{{--                @foreach ($table->columns as $column)--}}
{{--                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">--}}
{{--                        {{ $row->{$column->attribute} ?? '' }}--}}
{{--                    </td>--}}
{{--                @endforeach--}}
{{--                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">--}}
{{--                    @foreach ($table->actions as $action)--}}
{{--                        <a href="{{ $action->url($row) }}" class="{{ $action->class }}">--}}
{{--                            {{ $action->label }}--}}
{{--                        </a>--}}
{{--                    @endforeach--}}
{{--                </td>--}}
{{--            </tr>--}}
{{--        @endforeach--}}
{{--        </tbody>--}}
{{--    </table>--}}
{{--</div>--}}
