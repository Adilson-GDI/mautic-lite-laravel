<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmailMarketing\CampaignController;
use App\Http\Controllers\EmailMarketing\ContactController;
use App\Http\Controllers\EmailMarketing\DashboardController;
use App\Http\Controllers\EmailMarketing\ListController;
use App\Http\Controllers\EmailMarketing\ProviderController;
use App\Http\Controllers\EmailMarketing\TrackingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('email-marketing.dashboard');
});

Route::get('/email/track/open/{token}', [TrackingController::class, 'open'])->name('email-marketing.track.open');
Route::get('/email/track/click/{token}', [TrackingController::class, 'click'])->name('email-marketing.track.click');
Route::get('/email/unsubscribe/{token}', [TrackingController::class, 'unsubscribe'])->name('email-marketing.unsubscribe');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate'])->name('login.store');
    Route::get('/setup', [AuthController::class, 'setup'])->name('setup');
    Route::post('/setup', [AuthController::class, 'storeSetup'])->name('setup.store');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'password'])->name('profile.password');
    Route::resource('users', UserController::class)->except('show');
});

Route::prefix('admin/email')->name('email-marketing.')->middleware('auth')->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');
    Route::resource('providers', ProviderController::class)->except('show');
    Route::post('providers/{provider}/test', [ProviderController::class, 'test'])->name('providers.test');
    Route::resource('contacts', ContactController::class)->except('show');
    Route::post('contacts/import', [ContactController::class, 'import'])->name('contacts.import');
    Route::resource('lists', ListController::class)->except('show');
    Route::post('campaigns/upload-image', [CampaignController::class, 'uploadImage'])->name('campaigns.upload-image');
    Route::get('campaigns/{campaign}/report', [CampaignController::class, 'report'])->name('campaigns.report');
    Route::resource('campaigns', CampaignController::class);
    Route::post('campaigns/{campaign}/start', [CampaignController::class, 'start'])->name('campaigns.start');
    Route::post('campaigns/{campaign}/pause', [CampaignController::class, 'pause'])->name('campaigns.pause');
    Route::post('campaigns/{campaign}/cancel', [CampaignController::class, 'cancel'])->name('campaigns.cancel');
    Route::post('campaigns/process-pending', [CampaignController::class, 'process'])->name('campaigns.process');
});
