<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\TwoFactorController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\CashEntryController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\PayoutController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/dashboard');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 2FA
    Route::get('/two-factor', [TwoFactorController::class, 'show'])->name('two-factor.show');
    Route::post('/two-factor/enable', [TwoFactorController::class, 'enable'])->name('two-factor.enable');
    Route::post('/two-factor/disable', [TwoFactorController::class, 'disable'])->name('two-factor.disable');
    Route::post('/two-factor/verify', [TwoFactorController::class, 'verify'])->name('two-factor.verify');

    // Employees
    Route::resource('employees', EmployeeController::class);

    // Cash Entries
    Route::resource('cash', CashEntryController::class);

    // Payroll
    Route::resource('payroll', PayrollController::class);

    // Payouts
    Route::post('/payouts', [PayoutController::class, 'store'])->name('payouts.store');
    Route::delete('/payouts/{payout}', [PayoutController::class, 'destroy'])->name('payouts.destroy');
    Route::get('/payouts/history/{payrollId}', [PayoutController::class, 'history'])->name('payouts.history');
});

require __DIR__.'/auth.php';
