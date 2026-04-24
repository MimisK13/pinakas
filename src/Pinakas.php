<?php

namespace Mimisk\Pinakas;

use Illuminate\Contracts\Pagination\Paginator as PaginatorContract;
use Mimisk\Pinakas\Actions\ActionGroup;
use Mimisk\Pinakas\Bulk\Bulk;
use Mimisk\Pinakas\Bulk\BulkAction;
use Mimisk\Pinakas\Pagination\Paginate;
use Mimisk\Pinakas\Pagination\PerPage;
use Mimisk\Pinakas\Pagination\Template as PaginationTemplate;
use Mimisk\Pinakas\Search\Search;
use Mimisk\Pinakas\Sort\Sort;
use Mimisk\Pinakas\States\EmptyState;
use Mimisk\Pinakas\Support\Icon;
use Mimisk\Pinakas\UI\UI;

class Pinakas
{
    protected ?string $model = null;

    public array $columns = [];

    public array $actions = [];

    public array $filters = [];

    protected Paginate $paginate;

    protected PerPage $perPage;

    protected PaginationTemplate $paginationTemplate;

    protected Search $search;

    protected Sort $sort;

    protected Bulk $bulk;

    protected UI $ui;

    protected EmptyState $emptyState;

    protected EmptyState $searchEmptyState;

    protected bool $showPerPageLabel = false;

    public function __construct()
    {
        $paginationConfig = (array) config('pinakas.pagination', []);
        $searchConfig = (array) config('pinakas.search', []);
        $sortingConfig = (array) config('pinakas.sorting', []);
        $bulkConfig = (array) config('pinakas.bulk', []);
        $uiConfig = (array) config('pinakas.ui', []);
        $emptyStateConfig = (array) config('pinakas.empty_state', []);

        $enabled = (bool) ($paginationConfig['enabled'] ?? false);
        $defaultPerPage = max(1, (int) ($paginationConfig['default_per_page'] ?? 15));
        $pageName = (string) ($paginationConfig['page_name'] ?? 'page');
        $perPageQueryName = (string) ($paginationConfig['per_page_query_name'] ?? 'per_page');
        $perPageOptions = (array) ($paginationConfig['per_page_options'] ?? [10, 25, 50]);
        $template = (string) ($paginationConfig['template'] ?? 'default');
        $this->showPerPageLabel = (bool) ($paginationConfig['show_label'] ?? false);

        $this->paginate = new Paginate($enabled, $defaultPerPage, $pageName);
        $this->perPage = new PerPage($perPageOptions, $perPageQueryName, null);
        $this->paginationTemplate = new PaginationTemplate($template);
        $this->search = new Search(
            (bool) ($searchConfig['enabled'] ?? false),
            (string) ($searchConfig['query_name'] ?? 'search'),
            (bool) ($searchConfig['show_label'] ?? true),
            isset($searchConfig['label']) ? (string) $searchConfig['label'] : 'Search',
            (string) ($searchConfig['placeholder'] ?? 'Search...'),
            isset($searchConfig['icon']) ? (string) $searchConfig['icon'] : 'magnifying-glass',
            isset($searchConfig['debounce_ms']) ? (int) $searchConfig['debounce_ms'] : 350,
            isset($searchConfig['min_chars']) ? (int) $searchConfig['min_chars'] : 3,
            (string) ($searchConfig['rounded'] ?? 'rounded-none'),
        );
        $this->sort = new Sort(
            (bool) ($sortingConfig['enabled'] ?? false),
            (string) ($sortingConfig['query_name'] ?? 'sort'),
            (string) ($sortingConfig['direction_query_name'] ?? 'direction'),
            (string) ($sortingConfig['default_direction'] ?? 'asc'),
            (string) ($sortingConfig['icon_position'] ?? 'right'),
        );
        $this->bulk = new Bulk(
            (array) ($bulkConfig['actions'] ?? []),
            (string) ($bulkConfig['selected_input_name'] ?? 'selected_ids'),
        );
        $this->ui = new UI(
            (string) ($uiConfig['accent_color'] ?? 'amber-600'),
            (bool) ($uiConfig['table_bordered'] ?? false),
            (string) ($uiConfig['table_rounded'] ?? 'rounded-xs'),
            (bool) ($uiConfig['table_striped'] ?? false),
            (bool) ($uiConfig['table_hoverable'] ?? true),
            (string) ($uiConfig['pagination_dropdown_rounded'] ?? 'rounded-none'),
            (string) ($uiConfig['action_button_rounded'] ?? 'rounded-none'),
            (string) ($uiConfig['action_dropdown_rounded'] ?? 'rounded-none'),
        );
        $this->emptyState = new EmptyState(
            (string) ($emptyStateConfig['title'] ?? 'No records found'),
            isset($emptyStateConfig['description']) ? (string) $emptyStateConfig['description'] : 'There are no rows available yet.',
        );
        $this->searchEmptyState = new EmptyState(
            (string) ($emptyStateConfig['search_title'] ?? 'No matching results'),
            isset($emptyStateConfig['search_description']) ? (string) $emptyStateConfig['search_description'] : 'Try a different keyword or clear your search.',
        );
    }

    public function model(string $model): self
    {
        $this->model = $model;
        return $this;
    }

    public function getModel(): ?string
    {
        if (!$this->model) {
            throw new \Exception("Model is not set in Pinakas.");
        }

        return $this->model;
    }

    public function paginate(?int $perPage = null, ?string $pageName = null, ?string $label = null): self
    {
        $resolvedPerPage = max(1, $perPage ?? $this->paginate->perPage());
        $resolvedPageName = (is_string($pageName) && trim($pageName) !== '')
            ? $pageName
            : $this->paginate->pageName();

        $this->paginate = Paginate::enabled($resolvedPerPage, $resolvedPageName);

        if ($label !== null) {
            $normalizedLabel = trim($label);

            if ($normalizedLabel === '') {
                $this->showPerPageLabel = false;
                $this->perPage->label('');
            } else {
                $this->showPerPageLabel = true;
                $this->perPage->label($normalizedLabel);
            }
        }

        return $this;
    }

    public function perPageOptions(array $options, string $queryName = 'per_page'): self
    {
        $this->perPage->configure($options, $queryName);

        return $this;
    }

    public function searchable(string $queryName = 'search'): self
    {
        $this->search->enable($queryName);

        return $this;
    }

    public function searchLabel(?string $label): self
    {
        $this->search->setShowLabel($label !== null && trim($label) !== '');
        $this->search->setLabel($label);

        return $this;
    }

    public function showSearchLabel(bool $show = true): self
    {
        $this->search->setShowLabel($show);

        return $this;
    }

    public function searchPlaceholder(?string $placeholder): self
    {
        $this->search->setPlaceholder($placeholder);

        return $this;
    }

    public function searchIcon(?string $icon = 'magnifying-glass'): self
    {
        $this->search->setIcon($icon);

        return $this;
    }

    public function searchDebounceMs(?int $milliseconds): self
    {
        $this->search->setDebounceMs($milliseconds);

        return $this;
    }

    public function emptyState(string $title, ?string $description = null): self
    {
        $this->emptyState->set($title, $description);

        return $this;
    }

    public function searchEmptyState(string $title, ?string $description = null): self
    {
        $this->searchEmptyState->set($title, $description);

        return $this;
    }

    public function searchMinChars(?int $minChars): self
    {
        $this->search->setMinChars($minChars);

        return $this;
    }

    public function searchRounded(string $rounded = 'rounded-none'): self
    {
        $this->search->setRounded($rounded);

        return $this;
    }

    public function sortable(string $queryName = 'sort', string $directionQueryName = 'direction'): self
    {
        $this->sort->enable($queryName, $directionQueryName);

        return $this;
    }

    public function sortIconPosition(string $position = 'right'): self
    {
        $this->sort->setIconPosition($position);

        return $this;
    }

    public function uiAccentColor(string $color = 'amber-600'): self
    {
        $this->ui->setAccentColor($color);

        return $this;
    }

    public function bordered(bool $state = true): self
    {
        $this->ui->setTableBordered($state);

        return $this;
    }

    public function tableRounded(string $rounded = 'rounded-xs'): self
    {
        $this->ui->setTableRounded($rounded);

        return $this;
    }

    public function striped(bool $state = true): self
    {
        $this->ui->setTableStriped($state);

        return $this;
    }

    public function hoverable(bool $state = true): self
    {
        $this->ui->setTableHoverable($state);

        return $this;
    }

    public function paginationDropdownRounded(string $rounded = 'rounded-none'): self
    {
        $this->ui->setPaginationDropdownRounded($rounded);

        return $this;
    }

    public function actionButtonRounded(string $rounded = 'rounded-none'): self
    {
        $this->ui->setActionButtonRounded($rounded);

        return $this;
    }

    public function actionDropdownRounded(string $rounded = 'rounded-none'): self
    {
        $this->ui->setActionDropdownRounded($rounded);

        return $this;
    }

    public function paginationTemplate(string $template): self
    {
        $this->paginationTemplate->use($template);

        return $this;
    }

    public function getData()
    {
        if (!is_string($this->model)) {
            throw new \Exception("The model is not defined or is invalid.");
        }

        $query = ($this->model)::query();

        $this->search->apply($query, $this->columns);
        $this->sort->apply($query, $this->columns);

        if ($this->paginate->isEnabled()) {
            $requestedPerPage = request()->integer($this->perPage->queryName());
            $perPage = $this->perPage->resolve($this->paginate->perPage(), $requestedPerPage);

            return $query->paginate(
                $perPage,
                ['*'],
                $this->paginate->pageName()
            );
        }

        return $query->get();
    }

    public function columns(array $columns): self
    {
        $this->columns = $columns;
        return $this;
    }

    public function actions(array $actions): self
    {
        foreach ($actions as $action) {
            if ($action instanceof ActionGroup) {
                $this->actions[] = $action->getActions();
            } else {
                $this->actions[] = $action;
            }
        }

        return $this;
    }

    public function getActions(): array
    {
        return $this->actions;
    }

    public function bulkActions(array $actions): self
    {
        $this->bulk->setActions($actions);

        return $this;
    }

    public function bulkSelectedInputName(string $name): self
    {
        $this->bulk->setSelectedInputName($name);

        return $this;
    }

    public function getBulkActions(): array
    {
        return $this->bulk->actions();
    }

    public function hasBulkActions(): bool
    {
        return $this->bulk->hasActions();
    }

    public function getBulkSelectedInputName(): string
    {
        return $this->bulk->selectedInputName();
    }

    public function getBulkActionLabel(mixed $action): string
    {
        if ($action instanceof BulkAction) {
            return $action->label();
        }

        return (string) ($action->label ?? $action['label'] ?? 'Bulk Action');
    }

    public function getBulkActionUrl(mixed $action): string
    {
        if ($action instanceof BulkAction) {
            return $action->getUrl();
        }

        $url = (string) ($action->url ?? $action['url'] ?? '#');

        return trim($url) !== '' ? $url : '#';
    }

    public function getBulkActionMethod(mixed $action): string
    {
        if ($action instanceof BulkAction) {
            return $action->getMethod();
        }

        $method = strtoupper(trim((string) ($action->method ?? $action['method'] ?? 'POST')));

        // For bulk actions, GET usually causes "go to URL" behavior instead of
        // a mutating bulk operation request. Normalize to POST by default.
        if ($method === 'GET') {
            return 'POST';
        }

        return in_array($method, ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'], true) ? $method : 'POST';
    }

    public function getBulkActionClass(mixed $action): string
    {
        if ($action instanceof BulkAction) {
            return $action->getClass();
        }

        return trim((string) ($action->class ?? $action['class'] ?? ''));
    }

    public function getBulkActionConfirm(mixed $action): ?string
    {
        if ($action instanceof BulkAction) {
            return $action->getConfirm();
        }

        $confirm = trim((string) ($action->confirm ?? $action['confirm'] ?? ''));

        return $confirm !== '' ? $confirm : null;
    }

    public function getBulkActionIcon(mixed $action): ?string
    {
        if ($action instanceof BulkAction) {
            return $this->resolveBulkIcon($action->getIcon());
        }

        return $this->resolveBulkIcon((string) ($action->icon ?? $action['icon'] ?? ''));
    }

    public function getBulkRowId(mixed $row): ?string
    {
        $id = $row->id ?? null;
        $normalized = trim((string) $id);

        return $normalized !== '' ? $normalized : null;
    }

    private function resolveBulkIcon(?string $icon): ?string
    {
        $normalized = trim((string) $icon);

        if ($normalized === '') {
            return null;
        }

        if (str_starts_with($normalized, '<svg')) {
            return $normalized;
        }

        return match (strtolower($normalized)) {
            'trash', 'delete' => Icon::delete(),
            'edit', 'pencil' => Icon::edit(),
            'view', 'eye' => Icon::view(),
            default => null,
        };
    }

    public function hasPagination($rows): bool
    {
        return $rows instanceof PaginatorContract;
    }

    public function getPerPageOptions(): array
    {
        return $this->perPage->options($this->paginate->perPage());
    }

    public function getPerPageQueryName(): string
    {
        return $this->perPage->queryName();
    }

    public function currentPerPage(): int
    {
        $requestedPerPage = request()->integer($this->perPage->queryName());

        return $this->perPage->resolve($this->paginate->perPage(), $requestedPerPage);
    }

    public function getPerPageLabel(): ?string
    {
        if (! $this->showPerPageLabel) {
            return null;
        }

        return $this->perPage->getLabel() ?? 'Per page';
    }

    public function getPaginationTemplateView(): ?string
    {
        return $this->paginationTemplate->view();
    }

    public function hasSearch(): bool
    {
        return $this->search->isEnabled();
    }

    public function getSearchQueryName(): string
    {
        return $this->search->queryName();
    }

    public function getSearchLabel(): ?string
    {
        if (! $this->search->showLabel()) {
            return null;
        }

        return $this->search->label() ?? 'Search';
    }

    public function getSearchPlaceholder(): string
    {
        return $this->search->placeholder();
    }

    public function getSearchIconView(): ?string
    {
        return $this->search->iconView();
    }

    public function getSearchDebounceMs(): int
    {
        return $this->search->debounceMs();
    }

    public function getSearchMinChars(): int
    {
        return $this->search->minChars();
    }

    public function getSearchRoundedClass(): string
    {
        return $this->search->rounded();
    }

    public function currentSearchTerm(): string
    {
        return $this->search->currentTerm();
    }

    public function getSearchableAttributes(): array
    {
        return $this->search->searchableAttributes($this->columns);
    }

    public function hasSorting(): bool
    {
        return $this->sort->isEnabled() && ! empty($this->sort->sortableAttributes($this->columns));
    }

    public function isSortableColumn($column): bool
    {
        return $this->hasSorting() && $this->sort->isSortableColumn($column);
    }

    public function getSortQueryName(): string
    {
        return $this->sort->queryName();
    }

    public function getSortDirectionQueryName(): string
    {
        return $this->sort->directionQueryName();
    }

    public function getCurrentSort(): ?string
    {
        return $this->sort->currentSort($this->columns);
    }

    public function getCurrentSortDirection(): string
    {
        return $this->sort->currentDirection($this->columns);
    }

    public function getSortIconPosition(): string
    {
        return $this->sort->iconPosition();
    }

    public function getUiAccentColor(): string
    {
        return $this->ui->accentCssColor();
    }

    public function isBordered(): bool
    {
        return $this->ui->tableBordered();
    }

    public function getTableRoundedClass(): string
    {
        return $this->ui->tableRounded();
    }

    public function isStriped(): bool
    {
        return $this->ui->tableStriped();
    }

    public function isHoverable(): bool
    {
        return $this->ui->tableHoverable();
    }

    public function getPaginationDropdownRoundedClass(): string
    {
        return $this->ui->paginationDropdownRounded();
    }

    public function getActionButtonRoundedClass(): string
    {
        return $this->ui->actionButtonRounded();
    }

    public function getActionDropdownRoundedClass(): string
    {
        return $this->ui->actionDropdownRounded();
    }

    public function getColumnSortQuery($column): array
    {
        if (! $this->isSortableColumn($column)) {
            return request()->query();
        }

        return $this->sort->queryFor(
            (string) $column->attribute,
            $this->columns,
            $this->paginate->pageName()
        );
    }

    public function getColumnSortIndicator($column): ?string
    {
        if (! $this->isSortableColumn($column)) {
            return null;
        }

        return $this->sort->indicator((string) $column->attribute, $this->columns);
    }

    public function renderColumnValue(mixed $column, mixed $row): mixed
    {
        if (!method_exists($column, 'getValueFromRow') || !method_exists($column, 'formatValue')) {
            return $row->{$column->attribute} ?? '';
        }

        $value = $column->getValueFromRow($row);

        return $column->formatValue($value, $row);
    }

    public function getColumnHeaderAlignClass(mixed $column): string
    {
        if (method_exists($column, 'headerAlignClass')) {
            return (string) $column->headerAlignClass();
        }

        return 'text-left';
    }

    public function getColumnCellAlignClass(mixed $column): string
    {
        if (method_exists($column, 'cellAlignClass')) {
            return (string) $column->cellAlignClass();
        }

        return 'text-left';
    }

    public function getColumnAlignValue(mixed $column): string
    {
        if (method_exists($column, 'alignValue')) {
            return (string) $column->alignValue();
        }

        return 'left';
    }

    public function getColumnHeaderAlignValue(mixed $column): string
    {
        if (method_exists($column, 'headerAlignValue')) {
            return (string) $column->headerAlignValue();
        }

        return 'left';
    }

    public function hasRows($rows): bool
    {
        if (is_array($rows)) {
            return count($rows) > 0;
        }

        if ($rows instanceof \Countable) {
            return count($rows) > 0;
        }

        return false;
    }

    public function isSearchEmptyState($rows): bool
    {
        return $this->hasSearch()
            && $this->currentSearchTerm() !== ''
            && ! $this->hasRows($rows);
    }

    public function isTableEmptyState($rows): bool
    {
        return ! $this->hasRows($rows) && ! $this->isSearchEmptyState($rows);
    }

    public function getEmptyStateTitle(): string
    {
        return $this->emptyState->title();
    }

    public function getEmptyStateDescription(): ?string
    {
        return $this->emptyState->description();
    }

    public function getSearchEmptyStateTitle(): string
    {
        return $this->searchEmptyState->title();
    }

    public function getSearchEmptyStateDescription(): ?string
    {
        return $this->searchEmptyState->description();
    }

}
