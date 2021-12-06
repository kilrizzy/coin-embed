import * as nanocurrency from 'nanocurrency';
import * as bip39 from 'bip39';

console.log('Nano Worker loaded');

let nanoAccountsToCreate = 1000;
let nanoSeed = null;
let nanoWordList = [];
let nanoAddresses = [];

self.addEventListener("message", function(e) {
    if(e.data === 'generateNanoSeed'){
        generateNanoSeed();
    }else{
        if(typeof e.data === 'object'){
            if(e.data.action === 'nanoGetSeedFromWordlistString'){
                nanoGetSeedFromWordlistString(e.data.wordlistString);
            }
        }
    }
}, false);

function nanoGetSeedFromWordlistString(wordlistString){
    try {
        nanoSeed = bip39.mnemonicToEntropy(wordlistString);
    }catch (error){
        postMessage({
            'service':'nano',
            'action': 'nanoGetSeedFromWordlistString',
            'error': error
        });
        return null;
    }
    generateNanoSecretKeys(true);
}

function generateNanoSeed(){
    nanocurrency.generateSeed().then(function(value){
        nanoSeed = value;
        console.log('Seed: '+nanoSeed);
        let mnemonic = bip39.entropyToMnemonic(nanoSeed);
        console.log('Mnemonic: '+nanoSeed);
        nanoWordList = mnemonic.split(" ");
        let postMessageData = {
            'seed': nanoSeed,
            'wordList': nanoWordList,
        };
        postMessage(postMessageData);
        generateNanoSecretKeys();
    });
}

function generateNanoSecretKeys(returnSecret=false){
    var t0 = performance.now();
    for(let secretKeyIndex=0;secretKeyIndex<nanoAccountsToCreate;secretKeyIndex++){
        let secretKey = nanocurrency.deriveSecretKey(nanoSeed,secretKeyIndex);
        let publicKey = nanocurrency.derivePublicKey(secretKey);
        let address = nanocurrency.deriveAddress(publicKey, { useNanoPrefix: true });
        let addressObject = {};
        if(returnSecret){
            addressObject = {
                "address": address,
                "index": secretKeyIndex,
                "secretKey": secretKey,
                "publicKey": publicKey,
            };
        }else{
            addressObject = {
                "address": address,
                "index": secretKeyIndex,
            };
        }
        nanoAddresses.push(addressObject);
        //console.log('Generated Secret Key: '+secretKey);
        //console.log('Generated Public Key: '+publicKey);
        //console.log('Generated Address: '+address);
        postMessage({'generatedAddress':addressObject,'totalAddresses': nanoAddresses.length});
    }
    var t1 = performance.now();
    console.log('Done Generating');
    console.log("Generating "+nanoAccountsToCreate+" addresses took " + (t1 - t0) + " milliseconds.");
    postMessage({'addresses':nanoAddresses});
    //this.nanoAddressesGenerated = true;
}
