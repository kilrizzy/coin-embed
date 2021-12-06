<template>
    <div>
        <div v-if="addresses.length === 0" class="bg-white shadow overflow-hidden sm:rounded-md p-4">
            <p class="text-grey-dark mb-4 text-sm">To get started, add the space-separated wordlist that was created when you added the Nano payment method to CoinEmbed.</p>
            <div>
                <field-group>
                    <field-label :required="true">Wordlist</field-label>
                    <textarea v-model="wordlistString" rows="3" class="w-full text-lg border-2 rounded border-blue-600 p-4 font-bold text-blue-700" data-gramm_editor="false" data-gramm="false"></textarea>
                </field-group>
            </div>
            <button-ui v-if="!loadingAddresses" theme="primary" v-on:click="setWordlist">Continue to Consolidation Manager</button-ui>
            <button-ui v-if="loadingAddresses" theme="default">Loading Addresses...</button-ui>
        </div>
        <div v-if="addresses.length > 0">
            <p class="text-grey-dark mb-4 text-sm">View details on your nano accounts below</p>
            <div v-if="accountBalances">
                <div class="text-center mb-1" v-if="!currentQueueItem">
                    <button-ui theme="primary" v-on:click="initiateAll">Move All Funds to Consolidation Address</button-ui>
                </div>
                <div class="mb-2" v-if="currentQueueItem">
                    <div class="bg-gray-800 rounded p-4 text-gray-100 text-center shadow-lg">
                        <div class="mb-2">
                            <svg class="animate-spin h-5 w-5 text-gray-200 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                        <div class="text-xs font-bold mb-1 truncate">{{ currentQueueItem.address }}</div>
                        <div class="text-blue-200 text-sm font-bold mb-1" v-if="currentQueueItem.action == 'pending'">Receiving Pending</div>
                        <div class="text-blue-200 text-sm font-bold mb-1" v-if="currentQueueItem.action == 'send'">Sending to Consolidation Address</div>
                        <div class="text-xs text-gray-400">{{ queue.length }} Tasks remaining</div>
                    </div>
                </div>
                <div class="mb-4 text-center">
                    <p class="text-xs text-gray-500">This will run through each account to receive pending items, and send them to the consolidation address. <br/>Please remain on the page during this process it may take a long time.</p>
                </div>
            </div>
            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                    <tr>
                        <th scope="col" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Index
                        </th>
                        <th scope="col" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Address
                        </th>
                        <th scope="col" class="px-6 py-3 bg-gray-50">
                            <div class="text-right">
                                <button-ui size="sm" v-on:click="checkPaymentMethodBalances"><i class="fas fa-sync"></i></button-ui>
                            </div>
                        </th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="address in addresses">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ address.index }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <a :href="'https://nanocrawler.cc/explorer/account/'+address.address+'/history'" target="_blank" class="hover:underline">{{ address.address }}</a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div v-if="currentAccountProcessing == address.address" class="flex justify-end">
                                <div class="flex text-green-400 font-bold text-sm">
                                    <svg class="animate-spin h-5 w-5 text-blue-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <div class="ml-2">Processing</div>
                                </div>
                            </div>
                            <div v-if="currentAccountProcessing != address.address && !accountBalances[address.address]" class="flex justify-end">
                                <div class="flex text-gray-400 font-bold text-sm">
                                    <svg class="animate-spin h-5 w-5 text-blue-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <div class="ml-2">Loading Balance</div>
                                </div>
                            </div>
                            <div v-if="currentAccountProcessing != address.address && accountBalances[address.address]" class="flex justify-end">
                                <div class="mr-1 self-center">
                                    <button-ui v-on:click="receivePendingForAccount(address)" theme="primary" size="sm" v-if="accountBalances[address.address] && accountBalances[address.address].pending > 0">Retrieve Pending</button-ui>
                                    <span v-if="accountBalances[address.address] && accountBalances[address.address].pending == 0" class="no-underline inline-block px-5 py-2 font-bold rounded-full text-gray-400">0 Pending</span>
                                </div>
                                <div class="self-center">
                                    <button-ui v-on:click="sendBalanceForAccount(address)" theme="primary" size="sm" v-if="accountBalances[address.address] && accountBalances[address.address].balance > 0">Send</button-ui>
                                    <span v-if="accountBalances[address.address] && accountBalances[address.address].balance == 0" class="no-underline inline-block px-5 py-2 font-bold rounded-full text-gray-400">0 Balance</span>
                                </div>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
    window.nanoConsolidation = {};
    window.nanoConsolidation.vConsolidator = null;
    window.nanoConsolidation.nanoSeed = null;
    window.nanoConsolidation.nanoWordList = [];
    window.nanoConsolidation.nanoAddresses = [];
    window.nanoConsolidation.nanoWorker = new Worker('/js/workers/nano-worker.js');
    window.nanoConsolidation.nanoWorker.onmessage = function(e) {
        if(e.data.hasOwnProperty('addresses')){
            window.nanoConsolidation.nanoAddresses = e.data.addresses;
            window.nanoConsolidation.vConsolidator.finishedCreatingAddresses(window.nanoConsolidation.nanoAddresses);
        }
        if(e.data.hasOwnProperty('service') && e.data.service == 'nano'){
            //TODO - migrate all watchers here + to this format
            if(e.data.action == 'nanoGetSeedFromWordlistString'){
                if(e.data.hasOwnProperty('error')){
                    alert(e.data.error);
                    window.location.reload();
                }
            }
        }
    };
    window.Livewire.on('accountsBalancesReceived', response => {
        window.nanoConsolidation.vConsolidator.setAccountBalances(response.balances);
    });
    window.Livewire.on('nanoPendingReceived', address => {
        window.nanoConsolidation.vConsolidator.checkPaymentMethodBalances();
        window.nanoConsolidation.vConsolidator.currentAccountProcessing = null;
    });
    window.Livewire.on('nanoBalanceSent', address => {
        window.nanoConsolidation.vConsolidator.checkPaymentMethodBalances();
        window.nanoConsolidation.vConsolidator.currentAccountProcessing = null;
    });
    export default {
        props: {
            consolidationAddress: {
                required: true,
            },
            firstExpectedAddress: {
                required: true,
            }
        },
        mounted() {
            let vm = this;
            let interval = setInterval(function(){
                vm.checkBalancesIfCurrentQueueItem();
            }, 5000);
        },
        data() {
            return {
                wordlistString: null,
                addresses: [],
                addressValues: [],
                loadingAddresses: false,
                accountBalances: {},
                currentAccountProcessing: null,
                queue: [],
                currentQueueItem: null,
                currentQueueStartedAt: null,
            }
        },
        methods: {
            setWordlist: function(){
                this.loadingAddresses = true;
                window.nanoConsolidation.vConsolidator = this;
                let wordlistString = this.wordlistString.trim();
                window.nanoConsolidation.nanoWorker.postMessage({
                    action: 'nanoGetSeedFromWordlistString',
                    wordlistString: wordlistString
                });
            },
            setAccountBalances: function(accountBalances){
                this.accountBalances = accountBalances;
                console.log(this.currentQueueStartedAt);
                console.log(Date.now());
                console.log(Date.now()-this.currentQueueStartedAt);
                // If more than 2min has passed, move on to the next task
                if(this.currentQueueItem && Math.abs(Date.now()-this.currentQueueStartedAt) > 120000){
                    this.setNextQueuedItemAndDispatch();
                    return;
                }
                // If balance is zero, assume we have finished our task and move on
                if(this.currentQueueItem && this.accountBalances[this.currentQueueItem.address]){
                    if(this.currentQueueItem.action == 'pending' && this.accountBalances[this.currentQueueItem.address].pending == 0){
                        if(this.accountBalances[this.currentQueueItem.address].balance > 0){
                            let hasQueuedSend = false;
                            for(let i=0;i<this.queue.length;i++){
                                if(this.queue[i].action == 'send' && this.queue[i].address == this.currentQueueItem.address){
                                    hasQueuedSend = true;
                                }
                            }
                            if(!hasQueuedSend){
                                this.queue[this.queue.length] = {
                                    action: 'send',
                                    address: this.currentQueueItem.address
                                };
                            }
                        }
                        this.setNextQueuedItemAndDispatch();
                    }
                    if(this.currentQueueItem.action == 'send' && this.accountBalances[this.currentQueueItem.address].balance == 0){
                        this.setNextQueuedItemAndDispatch();
                    }
                }
            },
            receivePendingForAccount: function(account){
                this.currentAccountProcessing = account.address;
                window.Livewire.emit('receivePendingForAccount', account);
            },
            sendBalanceForAccount: function(account){
                this.currentAccountProcessing = account.address;
                window.Livewire.emit('sendBalanceForAccount', account);
            },
            finishedCreatingAddresses(addresses){
                this.loadingAddresses = false;
                this.addresses = addresses;
                // Make sure the seed was to this account
                if(this.addresses[0].address != this.firstExpectedAddress){
                    alert('Oops, it looks like you might have used a seed for a different account! Check that you\'re in the correct team and try again. Expected ['+this.firstExpectedAddress+'] Received ['+this.addresses[0].address+']');
                    window.location.reload();
                    return;
                }
                // Make an array of just addresses
                this.addressValues = [];
                for(let i=0;i<this.addresses.length;i++){
                    this.addressValues[i] = this.addresses[i].address;
                }
                window.Livewire.emit('nanoSetAddressValues', this.addressValues);
                //
                this.checkPaymentMethodBalances();
            },
            checkPaymentMethodBalances: function(){
                window.Livewire.emit('nanoCheckPaymentMethodBalances', this.addressValues);
            },
            initiateQueue: function(){
                this.queue = [];
                for (const address in this.accountBalances) {
                    if(this.accountBalances[address].pending > 0){
                        this.queue[this.queue.length] = {action:'pending',address:address};
                    }
                }
                for (const address in this.accountBalances) {
                    if(this.accountBalances[address].balance > 0){
                        this.queue[this.queue.length] = {action:'send',address:address};
                    }
                }
                console.log(this.queue);
            },
            initiateAll: function(){
                this.initiateQueue();
                this.setNextQueuedItemAndDispatch();
            },
            setNextQueuedItemAndDispatch: function(){
                this.setNextQueuedItem();
                this.dispatchCurrentQueueItem();
            },
            setNextQueuedItem: function(){
                if(this.queue.length == 0){
                    this.currentQueueItem = null;
                    this.currentQueueStartedAt = null;
                    return;
                }
                this.currentQueueItem = this.queue[0];
                this.currentQueueStartedAt = Date.now();
                this.queue.shift();
            },
            dispatchCurrentQueueItem: function(){
                if(this.currentQueueItem){
                    let account = this.getAccountFromAddress(this.currentQueueItem.address);
                    if(this.currentQueueItem.action == 'pending'){
                        this.receivePendingForAccount(account);
                    }
                    if(this.currentQueueItem.action == 'send'){
                        this.sendBalanceForAccount(account);
                    }
                }
            },
            getAccountFromAddress: function(accountAddress){
                for(let i=0;i<this.addresses.length;i++){
                    if(this.addresses[i].address == accountAddress){
                        return this.addresses[i];
                    }
                }
                return null;
            },
            checkBalancesIfCurrentQueueItem: function(){
                console.log('checkBalancesIfCurrentQueueItem');
                if(!this.currentQueueItem){
                    return null;
                }
                console.log(this.currentQueueItem);
                this.checkPaymentMethodBalances();
            }

        }
    }
</script>
