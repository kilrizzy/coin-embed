<?php

namespace App\Http\Controllers\Account\Transactions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(){
        return view('account.transaction.index');
    }
}
