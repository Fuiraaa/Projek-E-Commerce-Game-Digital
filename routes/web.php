<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DeveloperDashboardController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $games = \App\Models\Game::where('status', 'active')->with('developer')->latest()->take(8)->get();
    return view('welcome', compact('games'));
})->name('home');

Route::get('/dashboard', function () {
    $user = auth()->user();

    if ($user->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }

    if ($user->isDeveloper()) {
        return $user->is_verified
            ? redirect()->route('developer.games.index')
            : redirect()->route('developer.dashboard');
    }

    return redirect()->route('store.index');
})->middleware(['auth'])->name('dashboard');

Route::get('/store', [\App\Http\Controllers\StoreController::class, 'index'])->name('store.index');
Route::get('/store/{game:slug}', [\App\Http\Controllers\StoreController::class, 'show'])->name('store.show');

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/wallet', [WalletController::class, 'index'])->name('wallet.index');
    Route::post('/wallet/topup', [WalletController::class, 'topup'])->name('wallet.topup');
});

Route::middleware(['auth', 'player'])->group(function () {
    Route::get('/library', [LibraryController::class, 'index'])->name('library.index');
    Route::post('/checkout/{game:slug}', [TransactionController::class, 'checkout'])->name('checkout');
    Route::get('/download/{game:slug}', [\App\Http\Controllers\GameDownloadController::class, 'download'])->name('game.download');
    Route::get('/launcher', [\App\Http\Controllers\LauncherController::class, 'download'])->name('launcher.download');
});

Route::middleware(['auth', 'developer'])->group(function () {
    Route::get('/developer/dashboard', [DeveloperDashboardController::class, 'index'])->name('developer.dashboard');
    Route::post('/developer/request-verification', [DeveloperDashboardController::class, 'requestVerification'])->name('developer.request-verification');
    Route::resource('developer/games', GameController::class)->names('developer.games');

    Route::get('/developer/reports', [ReportController::class, 'index'])->name('developer.reports.index');
    Route::get('/developer/reports/export-pdf', [ReportController::class, 'exportPdf'])->name('developer.reports.pdf');
    Route::get('/developer/reports/export-excel', [ReportController::class, 'exportExcel'])->name('developer.reports.excel');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::post('/admin/verify-developer/{user}', [AdminController::class, 'verifyDeveloper'])->name('admin.verify-developer');
    Route::post('/admin/reject-developer/{user}', [AdminController::class, 'rejectDeveloper'])->name('admin.reject-developer');
    Route::post('/admin/approve-game/{game}', [AdminController::class, 'approveGame'])->name('admin.approve-game');
    Route::post('/admin/reject-game/{game}', [AdminController::class, 'rejectGame'])->name('admin.reject-game');

    Route::get('/admin/users', [\App\Http\Controllers\Admin\UserManagementController::class, 'index'])->name('admin.users.index');
    Route::get('/admin/users/{user}', [\App\Http\Controllers\Admin\UserManagementController::class, 'show'])->name('admin.users.show');
    Route::post('/admin/users/{user}/toggle-suspend', [\App\Http\Controllers\Admin\UserManagementController::class, 'toggleSuspend'])->name('admin.users.toggle-suspend');
    Route::delete('/admin/users/{user}', [\App\Http\Controllers\Admin\UserManagementController::class, 'destroy'])->name('admin.users.destroy');
});

require __DIR__.'/auth.php';
