@php($rows = $table->getData())
@php($columnSpan = count($table->columns) + (!empty($table->getBulkActions()) ? 1 : 0) + (!empty($table->getActions()) ? 1 : 0))
@php($bulkRowsSource = $table->hasPagination($rows) ? $rows->items() : $rows)
@php($bulkRowIds = collect($bulkRowsSource)->map(fn ($row) => $table->getBulkRowId($row))->filter()->values()->all())
@php($bulkDialogId = 'pinakas-bulk-confirm-dialog')
@php($isBordered = $table->isBordered())

@include('pinakas::partials.styles')

<div
    x-data="{
        loading: false,
        selected: [],
        rowIds: @js($bulkRowIds),
        confirmMessage: '',
        pendingAction: null,
        pendingRowForm: null,
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
        submitRowAction(form, confirmMessage) {
            if (!form) return;

            if (confirmMessage) {
                this.pendingRowForm = form;
                this.confirmMessage = confirmMessage;
                this.$refs.bulkConfirmDialog?.showModal();
                return;
            }

            this.executeRowAction(form);
        },
        executeRowAction(form) {
            if (!form) return;

            this.loading = true;

            form.submit();
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
        confirmPendingAction() {
            this.$refs.bulkConfirmDialog?.close();

            if (this.pendingAction) {
                this.executeBulkAction(this.pendingAction.url, this.pendingAction.method);
                this.pendingAction = null;
            } else if (this.pendingRowForm) {
                this.executeRowAction(this.pendingRowForm);
                this.pendingRowForm = null;
            }

            this.confirmMessage = '';
        },
        cancelPendingAction() {
            this.$refs.bulkConfirmDialog?.close();
            this.pendingAction = null;
            this.pendingRowForm = null;
            this.confirmMessage = '';
        }
    }"
    x-on:submit="loading = true"
    x-on:click="if ($event.target.closest('a[href]')) { loading = true }"
    x-on:keydown.escape.window="cancelPendingAction()"
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

                                @if (!empty($table->getActions()))
                                    <td class="w-1 whitespace-nowrap px-4 py-2 text-right text-sm text-gray-700 {{ $isBordered ? 'border-r border-b border-gray-200 dark:border-gray-700' : '' }} dark:text-gray-200">
                                        <div class="flex items-center justify-end gap-2">
                                            @foreach ($table->getActions() as $action)
                                                @if (is_array($action))
                                                    @include('pinakas::partials.action-group', ['actionGroup' => $action, 'table' => $table])
                                                @else
                                                    @include('pinakas::partials.action', ['action' => $action])
                                                @endif
                                            @endforeach
                                        </div>
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

@include('pinakas::partials.confirm-modal', ['dialogId' => $bulkDialogId])
