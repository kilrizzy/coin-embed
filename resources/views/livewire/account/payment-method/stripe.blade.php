<div>
    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <h3 class="text-lg leading-6 font-bold text-gray-900 mb-4">
            Stripe API Settings
        </h3>

        @if($serviceConnection)
            <div class="p-2 rounded border border-green-400 bg-green-100 text-xs opacity-75 mb-4"><strong>Good news!</strong> It looks like we can connect to your Stripe account</div>
        @endif

        @if($serviceConnectionError)
            <div class="p-2 rounded border border-red-400 bg-red-100 text-xs opacity-75 mb-4"><strong>Connection Error:</strong> {{ $serviceConnectionError }}</div>
        @endif

        <div class="bg-white shadow overflow-hidden sm:rounded-md p-4">
            <p class="text-grey-dark mb-4 text-sm">To enable Stripe, you will need to create a <strong>restricted key</strong> for CoinEmbed to use. This ensures CoinEmbed has only the Stripe permissions that you allow.</p>
            <p class="text-grey-dark mb-4 text-sm">To do this from your Stripe Dashboard, Go to: Developers > API Keys and then click "Create Restricted Key". Give the key a name like "CoinEmbed", and allow the following permissions: [Charges (Write)]</p>
            <p class="text-grey-dark mb-4 text-sm">When complete, click "Create Key" to save. On the resulting page you will have access to your Publishable Key and the Key Token you created.</p>
            <div>
                <field-group>
                    <field-label :required="true">Publishable Key</field-label>
                    <field-input-text placeholder="pk_" wire:model="apiKeyPublic" required="required"></field-input-text>
                </field-group>
                <field-group>
                    <field-label :required="true">Restricted Key</field-label>
                    <field-input-text placeholder="rk_" wire:model="apiKeyPrivate" required="required" />
                </field-group>
            </div>
            <button-ui theme="primary" wire:click="$emit('save')">Save Settings</button-ui>
        </div>
    </div>
</div>
