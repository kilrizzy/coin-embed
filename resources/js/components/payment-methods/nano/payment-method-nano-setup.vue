<template>
    <div>

        <div v-if="!initiatedAccountGeneration">
            <h3 class="text-lg leading-6 font-bold text-gray-900 mb-4">
                How CoinEmbed works with Nano
            </h3>

            <div class="bg-white shadow overflow-hidden sm:rounded-md p-4 mb-4">
                <p class="text-grey-dark mb-4 text-sm">In order for CoinEmbed to track payments, we will a create fresh nano seed with 1000 generated addresses. Each transaction sends funds to one of these generated addresses.</p>
                <p class="text-grey-dark mb-4 text-sm">This seed is never stored on CoinEmbed, so it's critical that you save this in a safe space.</p>
                <p class="text-grey-dark mb-4 text-sm">When you're ready to consolidate all payments into a single address, you will re-enter your seed into our consolidation tool.</p>
                <button-ui theme="primary" v-on:click="generateNanoSeed">I understand, continue</button-ui>
            </div>
        </div>

        <div v-if="initiatedAccountGeneration && !userViewedWordlist" >
            <h3 class="text-lg leading-6 font-bold text-gray-900 mb-4">
                Save your seed
            </h3>

            <div class="bg-white shadow overflow-hidden sm:rounded-md p-4 mb-4">
                <p class="text-grey-dark mb-4 text-sm">Having this seed is the only way to have your funds sent to you. <strong>CoinEmbed does not store this so make sure to keep it somewhere safe!</strong></p>
                <p v-if="!wordlistDisplay" class="text-grey-dark mb-4 text-sm">Loading...</p>
                <div v-if="wordlistDisplay" id="seedLoaded">
                    <div class="mb-4">
                        <textarea rows="3" class="w-full text-lg border-2 rounded border-blue-600 p-4 font-bold text-blue-700" :value="wordlistDisplay"></textarea>
                    </div>
                    <button-ui theme="primary" v-on:click="setUserViewedWordlist">I have saved my wordlist, continue</button-ui>
                </div>
            </div>
        </div>

        <div v-if="userViewedWordlist && !userConfirmedWordlist">
            <h3 class="text-lg leading-6 font-bold text-gray-900 mb-4">
                Confirm your seed
            </h3>

            <div class="bg-white shadow overflow-hidden sm:rounded-md p-4 mb-4">
                <p class="text-grey-dark mb-4 text-sm">Let's make sure you have that right, below enter each word you saved, separated by a space</p>
                <div class="mb-4">
                    <textarea v-model="wordlistConfirmation" rows="3" class="w-full text-lg border-2 rounded border-blue-600 p-4 font-bold text-blue-700"></textarea>
                </div>
                <button-ui theme="primary" v-on:click="verifyWordlist">Verify</button-ui>
            </div>
        </div>

        <div v-if="userViewedWordlist && userConfirmedWordlist">
            <h3 class="text-lg leading-6 font-bold text-gray-900 mb-4">
                Generate Nano processing accounts
            </h3>

            <div class="bg-white shadow overflow-hidden sm:rounded-md p-4 mb-4">
                <p v-if="nanoAddresses.length < 1000" class="text-grey-dark mb-4 text-sm">Please wait while we generate accounts for this seed</p>
                <p v-if="nanoAddresses.length >= 1000" class="text-grey-dark mb-4 text-sm">Account generation complete!</p>
                <div class="shadow w-full bg-gray-200 mb-4">
                    <div class="bg-blue-600 text-xs leading-none py-1 text-center text-white" :style="'width:'+nanoAddressesPercent+'%'"><span>{{ nanoAddresses.length }}</span> / 1000 accounts</div>
                </div>
                <button-ui v-if="!saveGeneratedAccountsLoading && nanoAddresses.length >= 1000" theme="primary" v-on:click="saveGeneratedAccounts">Save and Continue</button-ui>
                <span v-if="saveGeneratedAccountsLoading" class="no-underline inline-block focus:outline-none px-6 py-3 font-bold shadow rounded-full bg-gray-600 text-gray-200">Saving...</span>
            </div>
        </div>

    </div>
</template>

<script>
    window.nanoSetup = {};
    window.nanoSetup.nanoSeed = null;
    window.nanoSetup.nanoWordList = [];
    window.nanoSetup.nanoAddresses = [];
    window.nanoSetup.nanoWorker = new Worker('/js/workers/nano-worker.js');
    window.nanoSetup.nanoWorker.onmessage = function(e) {
        if(e.data.hasOwnProperty('seed')){
            window.nanoSetup.nanoSeed = e.data.seed;
        }
        if(e.data.hasOwnProperty('wordList')){
            window.nanoSetup.nanoWordList = e.data.wordList;
        }
        if(e.data.hasOwnProperty('addresses')){
            window.nanoSetup.nanoAddresses = e.data.addresses;
        }
    };
    export default {
        data() {
            return {
                initiatedAccountGeneration: false,
                wordlist: [],
                wordlistConfirmation: null,
                userViewedWordlist: false,
                userConfirmedWordlist: false,
                saveGeneratedAccountsLoading: false,
                nanoAddresses: [],
            }
        },
        methods: {
            generateNanoSeed: function(){
                window.nanoSetup.nanoWorker.postMessage('generateNanoSeed');
                this.initiatedAccountGeneration = true;
                this.queryWordlistUntilAvailable();
            },
            queryWordlistUntilAvailable: function(){
                if(window.nanoSetup.nanoWordList.length > 0){
                    this.wordlist = window.nanoSetup.nanoWordList;
                }else{
                    setTimeout(this.queryWordlistUntilAvailable, 1000);
                }
            },
            setUserViewedWordlist: function(){
                this.userViewedWordlist = true;
            },
            verifyWordlist: function(){
                let input = this.wordlistConfirmation.toLowerCase().trim();
                if(input != this.wordlistDisplay){
                    alert('Error')
                }else{
                    this.userConfirmedWordlist = true;
                    this.queryAddressesUntilAvailable();
                }
            },
            queryAddressesUntilAvailable: function(){
                console.log('queryAddressesUntilAvailable', this.nanoAddresses.length);
                if(this.nanoAddresses.length < 1000){
                    this.nanoAddresses = window.nanoSetup.nanoAddresses;
                    setTimeout(this.queryAddressesUntilAvailable, 1000);
                }
            },
            saveGeneratedAccounts: function(){
                this.saveGeneratedAccountsLoading = true;
                window.Livewire.emit('storeGeneratedAccounts', this.nanoAddresses);
            }
        },
        computed: {
            wordlistDisplay: function(){
                return this.wordlist.join(' ');
            },
            nanoAddressesPercent: function(){
                return (this.nanoAddresses.length * 100) / 1000;
            }
        }

    }
</script>
