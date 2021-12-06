<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Widgets') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">

        <div class="mb-4">
            <button-ui href="{{ url('/account/widgets/create') }}" theme="primary">Add New Widget</button-ui>
        </div>

        @if(Auth::user()->currentTeam->widgets->count() > 0)
        <div class="bg-white shadow overflow-hidden sm:rounded-md">

            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                <tr>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                        Name
                    </th>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                        Payment Methods
                    </th>
                    <th class="px-6 py-3 bg-gray-50"></th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                @foreach(Auth::user()->currentTeam->widgets as $widget)
                    <tr>
                        <td class="px-6 py-4 whitespace-no-wrap">
                            {{ $widget->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-no-wrap">
                            <div class="flex">
                                @foreach($widget->paymentMethodKeys as $paymentMethodKey)
                                    <img class="h-10 w-10 mr-2 bg-blue-500 rounded-full" src="/img/payment-methods/icon-{{ $paymentMethodKey }}.png" alt="{{ $paymentMethodKey }}">
                                @endforeach
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-no-wrap text-right text-sm leading-5 font-medium">
                            <button-ui href="{{ url('/account/widgets/'.$widget->uuid) }}/demo" theme="primary">Show Embed Code</button-ui>
                            <button-ui href="{{ url('/account/widgets/'.$widget->uuid) }}" class="ml-2">Edit Widget</button-ui>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>


        </div>
        @endif

    </div>

</x-app-layout>
