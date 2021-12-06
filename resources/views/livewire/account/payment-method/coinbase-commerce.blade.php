<div>
    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <h3 class="text-lg leading-6 font-bold text-gray-900 mb-4">
            Coinbase Commerce Settings
        </h3>

        @if($serviceConnection)
            <div class="p-2 rounded border border-green-400 bg-green-100 text-xs opacity-75 mb-4"><strong>Good news!</strong> It looks like we can connect to your Coinbase Commerce account</div>
        @endif

        @if($serviceConnectionError)
            <div class="p-2 rounded border border-red-400 bg-red-100 text-xs opacity-75 mb-4"><strong>Connection Error:</strong> {{ $serviceConnectionError }}</div>
        @endif

        <div class="bg-white shadow overflow-hidden sm:rounded-md p-4">
            <p class="text-grey-dark mb-4 text-sm">To enable CoinbaseCommerce, you will need to create an <strong>API Key</strong> for CoinEmbed to use.</p>
            <p class="text-grey-dark mb-4 text-sm">To do this from your <a href="https://commerce.coinbase.com/dashboard/">CoinbaseCommerce Dashboard</a>, Go to: Settings > API Keys and then click "Create an API Key".</p>
            <div>
                <field-group>
                    <field-label :required="true">API Key</field-label>
                    <field-input-text placeholder="" wire:model="apiKeyPrivate" required="required" />
                </field-group>
            </div>
            <button-ui theme="primary" wire:click="$emit('save')">Save Settings</button-ui>
        </div>
    </div>
</div>