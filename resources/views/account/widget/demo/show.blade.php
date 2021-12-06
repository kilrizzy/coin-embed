<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $widget->name }} Demo
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-10 px-6 sm:px-6 lg:px-8">

        <div class="lg:flex justify-between">
            <div class="lg:mr-4 w-full">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Add to your website</h3>
                <div class="mb-8 prose max-w-none">
                    <p class="mb-4">Add this line of code within the <code>&lt;header&gt;</code> tags of your website</p>

                    <pre><?php echo htmlentities('<script src="'.url('/v1/coinembed.js').'"></script>'); ?></pre>

                    <p class="mb-4">Add this piece of code where you would like the button that triggers the widget to appear</p>

                    <div class="mb-4">
                        <pre><?php echo htmlentities('<div
    rel="coinembed-widget"
    data-ce-id="'.$widget->uuid.'"
    data-ce-amount="0.99"
    data-ce-currency="usd"
    data-ce-name="My Product Name"
    data-ce-image="https://www.fillmurray.com/100/100"
    data-ce-callback="paymentComplete"
></div>'); ?></pre>
                    </div>

                    <div>View our <a href="{{ url('/docs') }}">documentation</a> for more information on callbacks, attributes, and validating responses!</div>
                </div>
            </div>
            <div class="">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Test your widget below</h3>
                <div rel="coinembed-widget"
                    data-ce-id="{{ $widget->uuid }}"
                    data-ce-frame-source-url="{{ url('/frame') }}"
                    data-ce-api-url="{{ url('/api') }}"
                    data-ce-amount="{{ request()->get('amount','0.99') }}"
                    data-ce-currency="usd"
                    data-ce-name="{{ request()->get('name','My Product Name') }}"
                    data-ce-image="{{ request()->get('image','https://www.fillmurray.com/100/100') }}"
                    data-ce-callback="widgetCallbackMethod"
                >Loading...</div>
            </div>
        </div>
    </div>

    @section('scripts')
        <script src="{{ asset('/v1/coinembed.js') }}"></script>
        <script>
            function widgetCallbackMethod(data){
                console.log('widgetCallbackMethod',data);
                alert('Payment success: '+data);
            }
        </script>
    @endsection

</x-app-layout>
