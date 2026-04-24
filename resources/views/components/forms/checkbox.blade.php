@php($inputId = isset($id) ? (string) $id : null)
@php($inputName = isset($name) ? (string) $name : null)
@php($inputValue = isset($value) ? (string) $value : null)
@php($inputModel = isset($model) ? (string) $model : null)
@php($inputChecked = isset($checked) ? (string) $checked : null)
@php($inputChange = isset($change) ? (string) $change : null)
@php($inputIndeterminate = isset($indeterminate) ? (string) $indeterminate : null)
@php($describedBy = isset($describedBy) ? (string) $describedBy : null)

@once
    <style>
        .pinakas-checkbox:checked,
        .pinakas-checkbox:indeterminate {
            background-color: var(--pinakas-accent) !important;
            border-color: var(--pinakas-accent) !important;
        }

        .pinakas-checkbox:focus-visible {
            outline-color: var(--pinakas-accent) !important;
        }

        .pinakas-checkbox:focus,
        .pinakas-checkbox:focus-visible {
            --tw-ring-opacity: 1 !important;
            --tw-ring-color: var(--pinakas-accent) !important;
        }
    </style>
@endonce

<div class="flex h-6 shrink-0 items-center">
    <div class="group grid size-4 grid-cols-1">
        <input
            @if($inputId) id="{{ $inputId }}" @endif
            @if($inputName) name="{{ $inputName }}" @endif
            @if($inputValue !== null) value="{{ $inputValue }}" @endif
            @if($describedBy) aria-describedby="{{ $describedBy }}" @endif
            @if(!empty($disabled)) disabled @endif
            @if($inputModel) x-model="{{ $inputModel }}" @endif
            @if($inputChecked) x-bind:checked="{{ $inputChecked }}" @endif
            @if($inputChange) x-on:change="{{ $inputChange }}" @endif
            @if($inputIndeterminate) x-effect="$el.indeterminate = {{ $inputIndeterminate }}" @endif
            type="checkbox"
            class="pinakas-checkbox col-start-1 row-start-1 appearance-none rounded border border-gray-300 bg-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 disabled:border-gray-300 disabled:bg-gray-100 disabled:checked:bg-gray-100 dark:border-white/10 dark:bg-white/5 dark:disabled:border-white/5 dark:disabled:bg-white/10 dark:disabled:checked:bg-white/10 forced-colors:appearance-auto"
        />
        <svg viewBox="0 0 14 14" fill="none" class="pointer-events-none col-start-1 row-start-1 size-3.5 self-center justify-self-center stroke-white group-has-[:disabled]:stroke-gray-950/25 dark:group-has-[:disabled]:stroke-white/25">
            <path d="M3 8L6 11L11 3.5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="opacity-0 group-has-[:checked]:opacity-100" />
            <path d="M3 7H11" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="opacity-0 group-has-[:indeterminate]:opacity-100" />
        </svg>
    </div>
</div>
