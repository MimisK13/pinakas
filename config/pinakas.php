<?php

return [

    'ui' => [
        /*
        |--------------------------------------------------------------------------
        | Global Accent Color
        |--------------------------------------------------------------------------
        |
        | This value controls the global accent color used by interactive UI
        | elements (for example: bulk checkboxes and active pagination state).
        |
        | Accepted values:
        | - Tailwind token keys supported by Pinakas (example: amber-600)
        | - CSS color values (example: #d97706, rgb(217,119,6))
        |
        */
        'accent_color' => 'amber-600',

        /*
        |--------------------------------------------------------------------------
        | Table Outer Border
        |--------------------------------------------------------------------------
        |
        | This value determines whether Pinakas tables render with an outer
        | border by default. You can override this per table via ->bordered().
        |
        */
        'table_bordered' => false,

        /*
        |--------------------------------------------------------------------------
        | Table Rounded Class
        |--------------------------------------------------------------------------
        |
        | This value controls the table rounded corners class. You may pass
        | any Tailwind rounded utility class (example: rounded-none, rounded-sm,
        | rounded-md, rounded-lg, rounded-xl, rounded-2xl).
        |
        */
        'table_rounded' => 'rounded-xs',

        /*
        |--------------------------------------------------------------------------
        | Table Striped Rows
        |--------------------------------------------------------------------------
        |
        | This value determines whether table rows use alternating striped
        | backgrounds by default. You can override this per table via
        | ->striped().
        |
        */
        'table_striped' => false,

        /*
        |--------------------------------------------------------------------------
        | Table Hoverable Rows
        |--------------------------------------------------------------------------
        |
        | This value determines whether table rows change background color on
        | hover by default. You can override this per table via ->hoverable().
        |
        */
        'table_hoverable' => true,

        /*
        |--------------------------------------------------------------------------
        | Pagination Dropdown Rounded Class
        |--------------------------------------------------------------------------
        |
        | This value controls the rounded corners class for the pagination
        | per-page dropdown trigger and dropdown panel.
        |
        */
        'pagination_dropdown_rounded' => 'rounded-none',

        /*
        |--------------------------------------------------------------------------
        | Action Button Rounded Class
        |--------------------------------------------------------------------------
        |
        | This value controls the rounded corners class for row action buttons
        | and the global bulk action button.
        |
        */
        'action_button_rounded' => 'rounded-none',

        /*
        |--------------------------------------------------------------------------
        | Action Dropdown Rounded Class
        |--------------------------------------------------------------------------
        |
        | This value controls the rounded corners class for row action dropdowns
        | and the global bulk action dropdown panel.
        |
        */
        'action_dropdown_rounded' => 'rounded-none',
    ],

    'columns' => [
        /*
        |--------------------------------------------------------------------------
        | Default Date Format
        |--------------------------------------------------------------------------
        |
        | This value is the default output format used by DateColumn when no
        | explicit ->format(...) is defined on the column.
        |
        */
        'date_format' => 'd-m-Y',

        /*
        |--------------------------------------------------------------------------
        | Default Time Format
        |--------------------------------------------------------------------------
        |
        | This value is the default output format used by TimeColumn when no
        | explicit ->format(...) is defined on the column.
        |
        */
        'time_format' => 'H:i',
    ],

    'empty_state' => [
        /*
        |--------------------------------------------------------------------------
        | Table Empty State
        |--------------------------------------------------------------------------
        |
        | These values are shown when the table has no data at all.
        |
        */
        'title' => 'No records found',
        'description' => 'There are no rows available yet.',

        /*
        |--------------------------------------------------------------------------
        | Search Empty State
        |--------------------------------------------------------------------------
        |
        | These values are shown when a search term is applied but no matching
        | rows are found.
        |
        */
        'search_title' => 'No matching results',
        'search_description' => 'Try a different keyword or clear your search.',
    ],

    'search' => [
        /*
        |--------------------------------------------------------------------------
        | Enable Search
        |--------------------------------------------------------------------------
        |
        | This value determines whether search is enabled by default for
        | Pinakas tables. It can be overridden per table with ->searchable().
        |
        */
        'enabled' => false,

        /*
        |--------------------------------------------------------------------------
        | Search Query Name
        |--------------------------------------------------------------------------
        |
        | This value defines the query-string key used for search terms
        | (for example: ?search=john).
        |
        */
        'query_name' => 'search',

        /*
        |--------------------------------------------------------------------------
        | Show Search Label
        |--------------------------------------------------------------------------
        |
        | This value determines whether the search label is shown above the
        | search input by default.
        |
        */
        'show_label' => false,

        /*
        |--------------------------------------------------------------------------
        | Search Label
        |--------------------------------------------------------------------------
        |
        | This value controls the default search label text.
        |
        */
        'label' => 'Search',

        /*
        |--------------------------------------------------------------------------
        | Search Placeholder
        |--------------------------------------------------------------------------
        |
        | This value controls the default placeholder text shown inside
        | the search input.
        |
        */
        'placeholder' => 'Search...',

        /*
        |--------------------------------------------------------------------------
        | Search Rounded Class
        |--------------------------------------------------------------------------
        |
        | This value controls the search input rounded corners class. You may
        | pass any Tailwind rounded utility class (example: rounded-none,
        | rounded-sm, rounded-md, rounded-lg).
        |
        */
        'rounded' => 'rounded-none',

        /*
        |--------------------------------------------------------------------------
        | Search Debounce (Milliseconds)
        |--------------------------------------------------------------------------
        |
        | This value controls the delay before the search form auto-submits
        | while the user is typing.
        |
        */
        'debounce_ms' => 350,

        /*
        |--------------------------------------------------------------------------
        | Search Minimum Characters
        |--------------------------------------------------------------------------
        |
        | This value controls the minimum number of characters required before
        | auto-submitting search. Empty input will always submit to clear
        | results.
        |
        */
        'min_chars' => 3,

        /*
        |--------------------------------------------------------------------------
        | Search Icon
        |--------------------------------------------------------------------------
        |
        | This value controls the icon rendered inside the search input.
        | Available:
        | - magnifying-glass
        | - search
        | - null (hide icon)
        | You may also pass a custom view name (example: vendor::icon.search).
        |
        */
        'icon' => 'magnifying-glass',
    ],

    'sorting' => [
        /*
        |--------------------------------------------------------------------------
        | Enable Sorting
        |--------------------------------------------------------------------------
        |
        | This value determines whether sorting is enabled by default for
        | Pinakas tables. It can be overridden per table with ->sortable().
        |
        */
        'enabled' => false,

        /*
        |--------------------------------------------------------------------------
        | Sort Query Name
        |--------------------------------------------------------------------------
        |
        | This value defines the query-string key used for the selected
        | sortable column attribute (for example: ?sort=name).
        |
        */
        'query_name' => 'sort',

        /*
        |--------------------------------------------------------------------------
        | Sort Direction Query Name
        |--------------------------------------------------------------------------
        |
        | This value defines the query-string key used for sort direction
        | (for example: ?direction=asc).
        |
        */
        'direction_query_name' => 'direction',

        /*
        |--------------------------------------------------------------------------
        | Default Sort Direction
        |--------------------------------------------------------------------------
        |
        | This value controls the first direction applied when a sortable
        | column is clicked for the first time. Allowed: asc, desc.
        |
        */
        'default_direction' => 'asc',

        /*
        |--------------------------------------------------------------------------
        | Sort Icon Position
        |--------------------------------------------------------------------------
        |
        | This value controls where the sortable icon appears inside each
        | sortable table header cell. Allowed: left, right.
        |
        */
        'icon_position' => 'right',
    ],

    'bulk' => [
        /*
        |--------------------------------------------------------------------------
        | Selected IDs Input Name
        |--------------------------------------------------------------------------
        |
        | This value defines the request input key used to submit selected row
        | IDs to bulk action endpoints.
        |
        */
        'selected_input_name' => 'selected_ids',

        /*
        |--------------------------------------------------------------------------
        | Default Bulk Actions
        |--------------------------------------------------------------------------
        |
        | You may define global bulk actions here. Most projects will configure
        | bulk actions per table via ->bulkActions([...]).
        |
        */
        'actions' => [
            //
        ],
    ],

    'pagination' => [
        /*
        |--------------------------------------------------------------------------
        | Enable Pagination
        |--------------------------------------------------------------------------
        |
        | This value determines whether pagination is enabled by default for
        | Pinakas tables. It can be overridden per table with ->paginate().
        |
        */
        'enabled' => false,

        /*
        |--------------------------------------------------------------------------
        | Default Per Page
        |--------------------------------------------------------------------------
        |
        | This value controls the default number of rows displayed per page
        | when pagination is enabled.
        |
        */
        'default_per_page' => 10,

        /*
        |--------------------------------------------------------------------------
        | Page Query Name
        |--------------------------------------------------------------------------
        |
        | This value defines the query-string key used for the current page
        | number (for example: ?page=2).
        |
        */
        'page_name' => 'page',

        /*
        |--------------------------------------------------------------------------
        | Per Page Query Name
        |--------------------------------------------------------------------------
        |
        | This value defines the query-string key used for the selected
        | per-page size (for example: ?per_page=25).
        |
        */
        'per_page_query_name' => 'per_page',

        /*
        |--------------------------------------------------------------------------
        | Per Page Options
        |--------------------------------------------------------------------------
        |
        | These values are the selectable options shown in the per-page
        | dropdown. You can override them per table via ->perPageOptions().
        |
        */
        'per_page_options' => [10, 25, 50],

        /*
        |--------------------------------------------------------------------------
        | Show Per Page Label
        |--------------------------------------------------------------------------
        |
        | This value determines whether the per-page label is shown above the
        | selector by default. Label text can still be overridden per table.
        |
        */
        'show_label' => false,

        /*
        |--------------------------------------------------------------------------
        | Pagination Template
        |--------------------------------------------------------------------------
        |
        | This value controls which pagination variation will be rendered by
        | default for Pinakas tables. You may override this per table with
        | ->paginationTemplate('...').
        |
        | Available:
        | - default,
        | - centered-page-numbers
        |
        */
        'template' => 'centered-page-numbers',
    ],
];
