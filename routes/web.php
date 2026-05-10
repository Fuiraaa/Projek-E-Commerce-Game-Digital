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

    Route::get('/cart', [\App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{game:slug}', [\App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
    Route::delete('/cart/remove/{cart}', [\App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/checkout', [\App\Http\Controllers\CartController::class, 'checkout'])->name('cart.checkout');

    Route::get('/order/{order_number}', [\App\Http\Controllers\OrderController::class, 'pay'])->name('order.pay');
    Route::post('/order/{order_number}/process', [\App\Http\Controllers\OrderController::class, 'processPayment'])->name('order.process');
    Route::get('/order/{order_number}/midtrans', [\App\Http\Controllers\OrderController::class, 'midtrans'])->name('order.midtrans');
    Route::get('/order/{order_number}/success', [\App\Http\Controllers\OrderController::class, 'success'])->name('order.success');
    Route::delete('/order/{order_number}/cancel', [\App\Http\Controllers\OrderController::class, 'cancel'])->name('order.cancel');
});

Route::post('/webhook/midtrans', [\App\Http\Controllers\OrderController::class, 'webhook'])->name('webhook.midtrans');

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

    Route::get('/admin/orders', [AdminController::class, 'orders'])->name('admin.orders');
    Route::post('/admin/orders/{order}/approve', [AdminController::class, 'approveOrder'])->name('admin.approve-order');
    Route::delete('/admin/orders/{order}', [AdminController::class, 'destroyOrder'])->name('admin.orders.destroy');
});

require __DIR__.'/auth.php';
