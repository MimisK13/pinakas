
        <div class=" rounded-sm shadow-md"> <!--- overflow-x-auto --->
            <!-- Bulk Actions -->
            @if (!empty($table->getBulkActions()))
                <div class="flex items-center space-x-2 mb-4">
                    @foreach ($table->getBulkActions() as $action)
                        <button
                            x-on:click="performBulkAction('{{ $action->method }}')"
                            class="{{ $action->class }}">
                            {{ $action->label }}
                        </button>
                    @endforeach
                </div>
            @endif

            <table class="min-w-full border border-gray-200 rounded-sm">
                <thead class="bg-gray-50">
                    <tr>
                        @if (!empty($table->getBulkActions()))
                            <th scope="col" class="px-4 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider border-r border-gray-200">
                                <input type="checkbox" x-model="selected">
                            </th>
                        @endif

                        @foreach ($table->columns as $column)
                            <th scope="col" class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border-r border-gray-200">
                                {{ $column->name }}
                            </th>
                        @endforeach
                    </tr>
                </thead>


                <tbody class="bg-white">
                    @foreach ($table->getData() as $row)
                        <tr class="hover:bg-gray-100 transition duration-200">
                            @if (!empty($table->getBulkActions()))
                                <td class="px-4 py-2 text-center whitespace-nowrap text-sm text-gray-700 border-b border-gray-200">
                                    <input type="checkbox" x-model="selected" value="{{ $row->id }}">
                                </td>
                            @endif

                            @foreach ($table->columns as $column)
                                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-700 border-b border-gray-200">
                                    {{ $row->{$column->attribute} ?? '' }}
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
                                <td class="px-4 py-2 text-sm text-gray-700 border-b border-gray-200">
                                    @foreach ($table->getActions() as $action)
                                        @if (is_array($action))
                                            <div class="flex space-x-2 justify-end">
                                                @include('pinakas::partials.action-group', ['actionGroup' => $action])
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
                </tbody>
            </table>
        </div>


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
