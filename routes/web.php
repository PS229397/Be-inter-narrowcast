<?php

use App\Http\Controllers\DisplayController;
use Filament\Facades\Filament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/app/impersonation/end', function (Request $request) {
    $adminId = $request->session()->pull('admin_impersonator_id');

    if (! $adminId) {
        abort(403);
    }

    Auth::guard('web')->logout();
    Auth::guard('admin')->loginUsingId($adminId);

    return redirect()->to(Filament::getPanel('admin')->getUrl());
})->middleware('web')->name('impersonation.end');

// Display pages (narrowcast screens)
Route::middleware('web')->group(function () {
    Route::get('/display/{customer}/login', [DisplayController::class, 'loginForm'])
        ->name('display.login');

    Route::post('/display/{customer}/login', [DisplayController::class, 'login'])
        ->name('display.auth');

    Route::get('/display/{customer}/{slideshow}', [DisplayController::class, 'show'])
        ->name('display.show');
});

// Display API (authenticated via display token cookie)
Route::get('/api/display/{customer}/{slideshow}/slides', [DisplayController::class, 'slides'])
    ->middleware('web')
    ->name('display.api.slides');
