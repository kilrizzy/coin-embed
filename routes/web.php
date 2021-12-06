<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    //return view('welcome');
    return view('frontend/home/index');
})->name('home');

if(env('DISABLE_REGISTRATION', false) == true){
    Route::get('/register', function () {
        return redirect()->to('/newsletter');
    })->name('register');
    Route::prefix('newsletter')->namespace('App\Http\Controllers\Frontend\Newsletter')->group(function(){
        Route::get('/', 'NewsletterController@index');
        Route::post('/', 'NewsletterController@store');
        Route::get('/complete', 'NewsletterController@show');
    });
}


Route::middleware(['auth:sanctum', 'verified'])->namespace('App\Http\Controllers')->group(function(){
    Route::prefix('dashboard')->namespace('Dashboard')->group(function(){
       Route::get('/', 'DashboardController@index')->name('dashboard');
    });
    Route::prefix('account')->namespace('Account')->group(function(){
        Route::prefix('payment-method')->namespace('PaymentMethods')->group(function(){
            Route::get('/', 'PaymentMethodController@index')->name('account.payment-method.index');
            Route::prefix('{paymentMethodKey}')->group(function(){
                Route::get('/', 'PaymentMethodController@show')->name('account.payment-method.show');
            });
        });
        Route::prefix('transaction')->namespace('Transactions')->group(function(){
            Route::get('/', 'TransactionController@index')->name('account.transactions.index');
        });
        Route::prefix('widgets')->namespace('Widgets')->group(function(){
            Route::get('/', 'WidgetController@index')->name('account.widgets.index');
            Route::get('/create', 'WidgetController@create')->name('account.widgets.create');
            Route::get('/{widgetUUID}', 'WidgetController@show');
            Route::get('/{widgetUUID}/demo', 'Demo\DemoController@show');
        });
        Route::prefix('pages')->namespace('Pages')->group(function(){
            Route::get('/', 'PageController@index')->name('account.pages.index');
        });
        Route::prefix('billing')->namespace('Billing')->group(function(){
            Route::get('/', 'BillingController@index')->name('account.billing.index');
        });
    });
});

Route::namespace('App\Http\Controllers')->group(function(){

    Route::prefix('/')->namespace('Frontend')->group(function(){
        Route::prefix('terms')->namespace('Terms')->group(function(){
            Route::get('/', 'TermsController@index');
        });
        Route::prefix('privacy')->namespace('Privacy')->group(function(){
            Route::get('/', 'PrivacyController@index');
        });
    });

    Route::prefix('docs')->namespace('Documentation')->group(function(){
        Route::get('/', 'DocumentationController@index');
    });
    Route::prefix('frame')->namespace('Frame')->group(function(){
        Route::prefix('v1')->namespace('V1')->group(function(){
            Route::prefix('widget')->namespace('Widgets')->group(function(){
                Route::get('/{widgetUUID}', 'WidgetController@show');
            });
        });
    });
    Route::namespace('QR')->prefix('/qr')->group(function(){
        Route::prefix('/{key}')->group(function(){
            Route::get('/', 'QRController@show');
        });
    });
});
