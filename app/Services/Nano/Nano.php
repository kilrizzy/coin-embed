<?php

namespace App\Services\Nano;

use deemru\Blake2b;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

class Nano
{

    private $client;
    private $host;

    public function __construct()
    {
        $this->client = new Client();
        $this->setHost(config('nano.node_uri'));
    }

    public function setHost($host){
        $this->host = $host;
    }

    private function getClient()
    {
        return $this->client;
    }

    private function getHost(){
        return $this->host;
    }

    public function call($action, $options = [])
    {
        $options['action'] = $action;
        $response = $this->getClient()->post($this->getHost(),
            [
                RequestOptions::JSON => $options
            ]
        );
        return json_decode($response->getBody()->getContents());
    }

    public function generateSeed(){
        return strtoupper(bin2hex(openssl_random_pseudo_bytes(32)));
    }

    public function deriveSecretKey($seed, $index) {
        $seedBytes = $this->hex2ByteArray($seed);
        $indexBytes =  sprintf('%08d', dechex($index));
        $indexBytes = $this->hex2ByteArray($indexBytes);
        $blake2b = new Blake2b();
        $context = array_merge($seedBytes, $indexBytes);
        $context = $this->byteArray2Hex($context);
        dump($context);
        $hash = $blake2b->hash($context); //TODO
        $hash = dechex($hash);
        dump($hash);
        /*
        var context = blake2bInit(32);
        blake2bUpdate(context, seed);
        blake2bUpdate(context, indexbytes);
        var resultingprivkey = blake2bFinal(context);
        privateKey = uint8_hex(resultingprivkey);
        */

    }

    //
    private function hex2ByteArray($hexString) {
        $string = hex2bin($hexString);
        return unpack('C*', $string);
    }

    private function byteArray2Hex($byteArray) {
        $chars = array_map("chr", $byteArray);
        $bin = join($chars);
        return bin2hex($bin);
    }


}