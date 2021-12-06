<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->get('/transaction/{transactionId}', function (Request $request, $transactionId) {
    $transaction = \App\Models\Transaction::findByUuid($transactionId);
    if(!$transaction){
        $transaction = \App\Models\Transaction::where('token',$transactionId)->first();
    }
    if(!$transaction){
        abort(404, 'Transaction not found');
    }
    $userTeamIds = [];
    foreach(\Illuminate\Support\Facades\Auth::user()->allTeams() as $team){
        $userTeamIds[] = $team->id;
    }
    if(!in_array($transaction->recipient_team_id, $userTeamIds)){
        abort(401, 'Invalid transaction access');
    }
    return new \App\Http\Resources\TransactionResource($transaction);
});
