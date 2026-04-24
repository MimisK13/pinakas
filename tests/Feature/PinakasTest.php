<?php

use Illuminate\Support\Collection;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Mimisk\Pinakas\Actions\Action;
use Mimisk\Pinakas\Actions\ActionGroup;
use Mimisk\Pinakas\Actions\DeleteAction;
use Mimisk\Pinakas\Bulk\BulkAction;
use Mimisk\Pinakas\Columns\Column;
use Mimisk\Pinakas\Pinakas;
use Mimisk\Pinakas\Tests\Fixtures\PinakasUser;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

it('loads package config and views', function () {
    $views = app(ViewFactory::class);

    expect(config('pinakas.ui.accent_color'))->toBe('amber-600')
        ->and($views->exists('pinakas::table'))->toBeTrue();
});

it('resolves configured ui defaults and allows fluent overrides', function () {
    config()->set('pinakas.ui.accent_color', 'emerald-600');
    config()->set('pinakas.ui.table_bordered', true);
    config()->set('pinakas.ui.table_rounded', 'rounded-lg');
    config()->set('pinakas.ui.table_striped', true);
    config()->set('pinakas.ui.table_hoverable', false);
    config()->set('pinakas.ui.pagination_dropdown_rounded', 'rounded-md');
    config()->set('pinakas.ui.action_button_rounded', 'rounded-sm');
    config()->set('pinakas.ui.action_dropdown_rounded', 'rounded-xl');

    $table = new Pinakas;

    expect($table->getUiAccentColor())->toBe('#059669')
        ->and($table->isBordered())->toBeTrue()
        ->and($table->getTableRoundedClass())->toBe('rounded-lg')
        ->and($table->isStriped())->toBeTrue()
        ->and($table->isHoverable())->toBeFalse()
        ->and($table->getPaginationDropdownRoundedClass())->toBe('rounded-md')
        ->and($table->getActionButtonRoundedClass())->toBe('rounded-sm')
        ->and($table->getActionDropdownRoundedClass())->toBe('rounded-xl');

    $table
        ->uiAccentColor('rose-600')
        ->bordered(false)
        ->tableRounded('rounded-none')
        ->striped(false)
        ->hoverable(true)
        ->paginationDropdownRounded('rounded-none')
        ->actionButtonRounded('rounded-md')
        ->actionDropdownRounded('rounded-lg');

    expect($table->getUiAccentColor())->toBe('#e11d48')
        ->and($table->isBordered())->toBeFalse()
        ->and($table->getTableRoundedClass())->toBe('rounded-none')
        ->and($table->isStriped())->toBeFalse()
        ->and($table->isHoverable())->toBeTrue()
        ->and($table->getPaginationDropdownRoundedClass())->toBe('rounded-none')
        ->and($table->getActionButtonRoundedClass())->toBe('rounded-md')
        ->and($table->getActionDropdownRoundedClass())->toBe('rounded-lg');
});

it('configures pagination search sorting and empty states with fluent api', function () {
    $table = (new Pinakas)
        ->paginate(25, 'users_page', 'Rows')
        ->perPageOptions([10, 25, 50], 'limit')
        ->paginationTemplate('centered-page-numbers')
        ->searchable('q')
        ->searchLabel('Find users')
        ->searchPlaceholder('Type a name')
        ->searchIcon('magnifying-glass')
        ->searchDebounceMs(500)
        ->searchMinChars(2)
        ->searchRounded('rounded-md')
        ->sortable('order_by', 'order_dir')
        ->sortIconPosition('left')
        ->columns([
            Column::make('Name', 'name')->sortable()->searchable(),
            Column::make('Email', 'email')->searchable(),
        ])
        ->emptyState('Nothing here', 'Create a record first.')
        ->searchEmptyState('No users found', 'Try another keyword.');

    expect($table->getPerPageOptions())->toBe([10, 25, 50])
        ->and($table->getPerPageQueryName())->toBe('limit')
        ->and($table->getPerPageLabel())->toBe('Rows')
        ->and($table->getPaginationTemplateView())->toBe('pinakas::pagination.centered-page-numbers')
        ->and($table->hasSearch())->toBeTrue()
        ->and($table->getSearchQueryName())->toBe('q')
        ->and($table->getSearchLabel())->toBe('Find users')
        ->and($table->getSearchPlaceholder())->toBe('Type a name')
        ->and($table->getSearchIconView())->toBe('pinakas::components.icons.magnifying-glass')
        ->and($table->getSearchDebounceMs())->toBe(500)
        ->and($table->getSearchMinChars())->toBe(2)
        ->and($table->getSearchRoundedClass())->toBe('rounded-md')
        ->and($table->hasSorting())->toBeTrue()
        ->and($table->getSortQueryName())->toBe('order_by')
        ->and($table->getSortDirectionQueryName())->toBe('order_dir')
        ->and($table->getSortIconPosition())->toBe('left')
        ->and($table->getSearchableAttributes())->toBe(['name', 'email'])
        ->and($table->getEmptyStateTitle())->toBe('Nothing here')
        ->and($table->getEmptyStateDescription())->toBe('Create a record first.')
        ->and($table->getSearchEmptyStateTitle())->toBe('No users found')
        ->and($table->getSearchEmptyStateDescription())->toBe('Try another keyword.');
});

it('normalizes bulk actions and row ids', function () {
    $action = BulkAction::make('Delete')
        ->url('/users/bulk-delete')
        ->method('DELETE')
        ->confirm('Delete selected users?')
        ->icon('trash');

    $table = (new Pinakas)
        ->bulkActions([$action])
        ->bulkSelectedInputName('users');

    expect($table->hasBulkActions())->toBeTrue()
        ->and($table->getBulkSelectedInputName())->toBe('users')
        ->and($table->getBulkActionLabel($action))->toBe('Delete')
        ->and($table->getBulkActionUrl($action))->toBe('/users/bulk-delete')
        ->and($table->getBulkActionMethod($action))->toBe('DELETE')
        ->and($table->getBulkActionConfirm($action))->toBe('Delete selected users?')
        ->and($table->getBulkActionIcon($action))->toContain('<svg')
        ->and($table->getBulkActionMethod(['method' => 'GET']))->toBe('POST')
        ->and($table->getBulkRowId((object) ['id' => 15]))->toBe('15')
        ->and($table->getBulkRowId((object) ['id' => null]))->toBeNull();
});

it('detects empty state variants from rows and search term', function () {
    $table = (new Pinakas)->searchable('search');

    request()->merge(['search' => 'missing']);

    expect($table->hasRows(new Collection))->toBeFalse()
        ->and($table->isSearchEmptyState(new Collection))->toBeTrue()
        ->and($table->isTableEmptyState(new Collection))->toBeFalse();

    request()->merge(['search' => '']);

    expect($table->isSearchEmptyState([]))->toBeFalse()
        ->and($table->isTableEmptyState([]))->toBeTrue();
});

it('renders single row actions', function () {
    $table = tableWithRows([(object) ['id' => 1, 'name' => 'Mimis']])
        ->columns([
            Column::make('Name', 'name'),
        ])
        ->actions([
            Action::make('Open')->url('/users/1'),
        ]);

    expect(view('pinakas::table', ['table' => $table])->render())
        ->toContain('Mimis')
        ->toContain('href="/users/1"')
        ->toContain('Open');
});

it('renders route actions and destroy actions with confirmation hooks', function () {
    Route::get('/users/{user}', fn () => null)->name('user.show');
    Route::delete('/pinakas-users/{pinakasuser}', fn () => null)->name('pinakasuser.destroy');

    $row = new PinakasUser(['name' => 'Mimis']);
    $row->id = 1;

    $table = tableWithRows([$row])
        ->columns([
            Column::make('Name', 'name'),
        ])
        ->actions([
            Action::make('Open')->route('user.show', fn ($row) => ['user' => $row->id]),
            DeleteAction::make(),
        ]);

    $html = view('pinakas::table', ['table' => $table])->render();

    expect($html)
        ->toContain('href="http://localhost/users/1"')
        ->toContain('action="http://localhost/pinakas-users/1"')
        ->toContain('name="_method" value="DELETE"')
        ->toContain('x-on:submit.prevent.stop')
        ->toContain('Are you sure you want to delete this record?');
});

it('renders grouped row actions', function () {
    $table = tableWithRows([(object) ['id' => 1, 'name' => 'Mimis']])
        ->columns([
            Column::make('Name', 'name'),
        ])
        ->actions([
            ActionGroup::make([
                Action::make('View')->url('/users/1'),
                Action::make('Edit')->url('/users/1/edit'),
            ]),
        ]);

    $html = view('pinakas::table', ['table' => $table])->render();

    expect($html)
        ->toContain('Mimis')
        ->toContain('href="/users/1"')
        ->toContain('href="/users/1/edit"')
        ->toContain('View')
        ->toContain('Edit');
});

function tableWithRows(array $rows): Pinakas
{
    return new class($rows) extends Pinakas {
        public function __construct(private array $rows)
        {
            parent::__construct();
        }

        public function getData(): Collection
        {
            return new Collection($this->rows);
        }
    };
}

it('applies search sort and pagination to eloquent queries', function () {
    createPinakasUsersTable();

    PinakasUser::query()->insert([
        ['name' => 'Beta', 'email' => 'beta@example.com'],
        ['name' => 'Alpha', 'email' => 'alpha@example.com'],
        ['name' => 'Gamma', 'email' => 'gamma@example.com'],
    ]);

    request()->merge([
        'search' => 'a',
        'sort' => 'name',
        'direction' => 'asc',
        'per_page' => 2,
    ]);

    $rows = (new Pinakas)
        ->model(PinakasUser::class)
        ->columns([
            Column::make('Name', 'name')->searchable()->sortable(),
            Column::make('Email', 'email')->searchable(),
        ])
        ->searchable()
        ->sortable()
        ->paginate(10)
        ->perPageOptions([2, 10])
        ->getData();

    expect($rows->total())->toBe(3)
        ->and($rows->perPage())->toBe(2)
        ->and(collect($rows->items())->pluck('name')->all())->toBe(['Alpha', 'Beta']);
});

it('falls back to all column attributes when no searchable columns are explicit', function () {
    createPinakasUsersTable();

    PinakasUser::query()->insert([
        ['name' => 'Mimis', 'email' => 'mimis@example.com'],
        ['name' => 'Other', 'email' => 'other@example.com'],
    ]);

    request()->merge(['search' => 'mimis@example.com']);

    $rows = (new Pinakas)
        ->model(PinakasUser::class)
        ->columns([
            Column::make('Name', 'name'),
            Column::make('Email', 'email'),
        ])
        ->searchable()
        ->getData();

    expect($rows)->toHaveCount(1)
        ->and($rows->first()->name)->toBe('Mimis');
});

function createPinakasUsersTable(): void
{
    Schema::dropIfExists('pinakas_users');
    Schema::create('pinakas_users', function (Blueprint $table): void {
        $table->id();
        $table->string('name');
        $table->string('email');
    });
}
