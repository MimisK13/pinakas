<?php

namespace Mimisk\Pinakas\Columns;

use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;

class TimeColumn extends Column
{
    private string $outputFormat;
    private ?string $inputFormat = null;
    private ?string $timezone = null;
    private string $emptyText = '';

    public static function make(?string $name = null, string $attribute = ''): self
    {
        return new self($name, $attribute);
    }

    public function __construct(?string $name = null, string $attribute = '')
    {
        parent::__construct($name, $attribute);

        $this->outputFormat = (string) config('pinakas.columns.time_format', 'H:i');
    }

    public function format(string $format): self
    {
        $trimmed = trim($format);
        $this->outputFormat = $trimmed !== '' ? $trimmed : 'H:i';

        return $this;
    }

    public function inputFormat(?string $format): self
    {
        $trimmed = trim((string) $format);
        $this->inputFormat = $trimmed !== '' ? $trimmed : null;

        return $this;
    }

    public function timezone(?string $timezone): self
    {
        $trimmed = trim((string) $timezone);
        $this->timezone = $trimmed !== '' ? $trimmed : null;

        return $this;
    }

    public function emptyText(string $text): self
    {
        $this->emptyText = $text;

        return $this;
    }

    public function formatValue(mixed $value, mixed $row): mixed
    {
        if ($value === null || $value === '') {
            return $this->emptyText;
        }

        $date = $this->toCarbon($value);
        if ($date === null) {
            return parent::formatValue($value, $row);
        }

        if ($this->timezone !== null) {
            $date = $date->copy()->setTimezone($this->timezone);
        }

        $formatted = $date->format($this->outputFormat);

        return parent::formatValue($formatted, $row);
    }

    private function toCarbon(mixed $value): ?Carbon
    {
        if ($value instanceof CarbonInterface) {
            return Carbon::instance($value);
        }

        if ($value instanceof \DateTimeInterface) {
            return Carbon::instance($value);
        }

        try {
            if ($this->inputFormat !== null && is_string($value)) {
                return Carbon::createFromFormat($this->inputFormat, $value);
            }

            return Carbon::parse((string) $value);
        } catch (\Throwable) {
            return null;
        }
    }
}
