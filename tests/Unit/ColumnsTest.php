<?php

use Illuminate\Support\Carbon;
use Mimisk\Pinakas\Columns\BadgeColumn;
use Mimisk\Pinakas\Columns\BooleanColumn;
use Mimisk\Pinakas\Columns\Column;
use Mimisk\Pinakas\Columns\DateColumn;
use Mimisk\Pinakas\Columns\TimeColumn;

it('resolves column labels values formatting and alignment', function () {
    $row = (object) [
        'name' => 'Mimis',
        'profile' => ['email' => 'mimis@example.com'],
    ];

    $column = Column::make(null, 'profile.email')
        ->align('center')
        ->headerAlign('right')
        ->cellAlign('left')
        ->formatUsing(fn ($value) => strtoupper($value));

    expect($column->name)->toBe('Profile Email')
        ->and($column->getValueFromRow($row))->toBe('mimis@example.com')
        ->and($column->formatValue('mimis@example.com', $row))->toBe('MIMIS@EXAMPLE.COM')
        ->and($column->headerAlignClass())->toBe('text-right')
        ->and($column->cellAlignClass())->toBe('text-left')
        ->and($column->headerAlignValue())->toBe('right')
        ->and($column->alignValue())->toBe('left');
});

it('formats date and time columns using config and fluent overrides', function () {
    config()->set('pinakas.columns.date_format', 'd/m/Y');
    config()->set('pinakas.columns.time_format', 'H:i');

    $row = (object) [];
    $value = Carbon::parse('2026-04-24 21:35:00', 'UTC');

    expect(DateColumn::make('Created', 'created_at')->formatValue($value, $row))->toBe('24/04/2026')
        ->and(TimeColumn::make('Created time', 'created_at')->formatValue($value, $row))->toBe('21:35')
        ->and(DateColumn::make('Created', 'created_at')->format('Y-m-d')->formatValue($value, $row))->toBe('2026-04-24')
        ->and(TimeColumn::make('Created time', 'created_at')->format('g:i A')->formatValue($value, $row))->toBe('9:35 PM')
        ->and(DateColumn::make('Missing', 'missing')->emptyText('-')->formatValue(null, $row))->toBe('-');
});

it('renders badge column with dynamic colors', function () {
    $html = BadgeColumn::make('Status', 'status')
        ->color(fn ($value) => $value === 'active' ? 'green' : 'gray')
        ->formatValue('active', (object) ['status' => 'active'])
        ->toHtml();

    expect($html)->toContain('active')
        ->and($html)->toContain('bg-green-50')
        ->and(BadgeColumn::make('Status', 'status')->formatValue('', (object) []))->toBe('');
});

it('renders boolean column labels and colors', function () {
    $column = BooleanColumn::make('Verified', 'email_verified_at')
        ->labels('Verified', 'Unverified')
        ->colors('green', 'red');

    $verified = $column->formatValue(true, (object) [])->toHtml();
    $unverified = $column->formatValue(false, (object) [])->toHtml();

    expect($verified)->toContain('Verified')
        ->and($verified)->toContain('bg-green-50')
        ->and($unverified)->toContain('Unverified')
        ->and($unverified)->toContain('bg-red-50');
});
