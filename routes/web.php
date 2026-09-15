<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{
    DashboardController,
    CarenderiaController,
    LoanController,
    GroceryController,
    PaymentsController,
    UserController,
    EmployeeBalanceController
};

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::prefix('carenderia')->group(function () {
    Route::get('/', [CarenderiaController::class, 'index'])->name('carenderia.index');
    Route::post('/upload', [CarenderiaController::class, 'upload'])->name('carenderia.upload');

});

Route::prefix('loan')->group(function () {
    Route::get('/', [LoanController::class, 'index'])->name('loan.index');
    Route::post('/upload', [LoanController::class, 'upload'])->name('loan.upload');

});

Route::prefix('grocery')->group(function () {
    Route::get('/', [GroceryController::class, 'index'])->name('grocery.index');
    Route::post('/upload', [GroceryController::class, 'upload'])->name('grocery.upload');

});

Route::prefix('payments')->group(function () {
    Route::get('/', [PaymentsController::class, 'index'])->name('payments.index');
    Route::post('/upload', [PaymentsController::class, 'upload'])->name('payments.upload');

});

Route::prefix('users')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('users.index');

    Route::post('/employee', [UserController::class, 'storeEmployee'])->name('users.store.employee');
    Route::post('/admin', [UserController::class, 'storeAdmin'])->name('users.store.admin');

});

Route::prefix('employee-balances')->group(function () {
    Route::post('/import', [EmployeeBalanceController::class, 'import'])->name('balances.import');
    Route::post('/search', [EmployeeBalanceController::class, 'search'])->name('balances.search');
});
