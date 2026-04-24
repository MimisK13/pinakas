# Changelog 

All notable changes to `Pinakas` will be documented in this file.

## v0.0.1 - 2026-04-24

### Added
- Pest test suite for package bootstrapping, fluent table configuration, bulk actions, empty states, and typed columns.
- GitHub Actions test workflow for the supported Laravel matrix.
- Larastan static analysis config and Composer script (`composer analyse`).
- README test badge and package requirements section.
- Pagination classes (`Pagination\Paginate`, `Pagination\PerPage`).
- `Pinakas::paginate(?int $perPage = null, ?string $pageName = null, ?string $label = null)`.
- `Pinakas::perPageOptions(array $options, string $queryName = 'per_page')`.
- Optional label text via `Pinakas::paginate(..., ..., 'Label')`.
- Global pagination defaults via `config('pinakas.pagination')`, including `show_label` boolean.
- Search feature via `Pinakas::searchable()` and `Column::searchable()`.
- Search UI options via `searchLabel()`, `searchPlaceholder()`, `searchIcon()`, and `showSearchLabel()`.
- Search debounce configuration via `search.debounce_ms` and `searchDebounceMs()`.
- Search minimum characters via `search.min_chars` (default 3) and `searchMinChars()`.
- Search input rounded class via `search.rounded` and `searchRounded()`.
- Empty state placeholders for both no table data and no search results.
- Pagination templates via config defaults and per-table override (`paginationTemplate()`).
- Reusable per-page dropdown partial using `el-select`.
- Reusable icon blade partials.
- Centralized action icons in `Support\Icon`.
- Publishable JS asset (`pinakas-assets`) for loading `@tailwindplus/elements`.
- Sorting feature via `Pinakas::sortable()` and `Column::sortable()`.
- Sorting settings in config (`sorting.enabled`, `sorting.query_name`, `sorting.direction_query_name`, `sorting.default_direction`).
- Sortable icon position setting (`sorting.icon_position`) with per-table override via `sortIconPosition()`.
- Reusable sortable table header partial and sort direction icons.
- Bulk actions feature via `Pinakas::bulkActions()` and `Bulk\BulkAction`.
- Bulk selected IDs input customization via `Pinakas::bulkSelectedInputName()`.
- Bulk config defaults (`bulk.selected_input_name`, `bulk.actions`).
- UI settings via `config('pinakas.ui')` and per-table `uiAccentColor()`.
- Reusable checkbox component for bulk selection (`components/forms/checkbox`).
- Bulk action icons via `BulkAction::icon()` (example: `->icon('trash')`).
- Typed column classes: `DateColumn`, `TimeColumn`, `BadgeColumn`, `BooleanColumn`.
- Column formatter pipeline (`formatUsing`) and HTML-safe cell rendering support.
- Column alignment support via `->align('left'|'center'|'right')`.
- Independent header alignment via `->headerAlign('left'|'center'|'right')`.
- Added `cellAlign('left'|'center'|'right')` for cell-only alignment.
- `align('...')` now applies to both header and cells.
- Table border toggle via `->bordered(bool)` and global `ui.table_bordered` config.
- Table rounded class setting via `->tableRounded('...')` and global `ui.table_rounded`.
- Table striped rows setting via `->striped(bool)` and global `ui.table_striped`.
- Table hoverable rows setting via `->hoverable(bool)` and global `ui.table_hoverable`.
- Rounded settings for pagination dropdowns and action controls via UI config and per-table methods.
- Global default date/time formats via `columns.date_format` and `columns.time_format`.
- Optional column header label in `make()` with auto-generated title from attribute.
- Single row actions are supported alongside grouped row actions.
- `Action::route()` and `Action::confirm()` for route-based URLs and confirmation modals.
- Eloquent integration tests for search, sorting, and pagination.

### Changed
- Package support now targets Laravel 11, 12, and 13. Laravel 10 support was removed.
- Action URL generation no longer depends on static `Pinakas::getModel()` calls.
- Pinakas model state is instance-based.
- Action rendering is now reusable via `partials/action-item`.
- Per-page dropdown submit now uses `closest('form')` (fix for undefined `this.form` on `el-select`).
- Per-page label is rendered above the dropdown.
- Loading overlay (spinner + opacity) is shown while table updates are being applied.
- Added dark mode styles across table controls and states.
- Table headers now support clickable sort toggles while preserving current query filters.
- Bulk action toolbar now submits selected row IDs with proper method/url and optional confirmation.
- `DeleteAction` now uses the Laravel-style `*.destroy` route convention.
- Row `DELETE` actions with confirmation now use the modal confirmation flow before submit.
- Indigo hardcoded checkbox/pagination accents replaced with global UI accent color.
