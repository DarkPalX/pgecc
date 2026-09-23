<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{
    DashboardController,
    CarenderiaController,
    LoanController,
    ConsumerBalanceController,
    GroceryController,
    PaymentsController,
    UserController,
    EmployeeBalanceController,
    MemberClassController,
    AuthController
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

Route::middleware('guest')->group(function () {
    // Route::get('/login', fn () => redirect()->route('dashboard', ['login' => 1]))->name('login');
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/profile/password', [AuthController::class, 'showChangePassword'])->middleware('auth')->name('profile.password');
    Route::put('/profile/password', [AuthController::class, 'updatePassword'])->middleware('auth')->name('profile.password.update');

    Route::get('/', fn () => redirect()->route('dashboard'));
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/file-uploads/{fileUpload}/download', [EmployeeBalanceController::class, 'download'])->name('file-uploads.download');
    Route::get('/module-file-uploads/{uploadedFile}/download', [EmployeeBalanceController::class, 'downloadModule'])->name('file-uploads.module.download');

    Route::prefix('member-classes')->name('member-classes.')->middleware(['auth', 'permission:manage_member_classes'])->group(function () {
            Route::get('/', [MemberClassController::class, 'index'])->name('index');
            Route::post('/', [MemberClassController::class, 'store'])->name('store');
            Route::put('/{memberClass}', [MemberClassController::class, 'update'])->name('update');
            Route::delete('/{memberClass}', [MemberClassController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('carenderia')->group(function () {
        Route::get('/', [CarenderiaController::class, 'index'])->name('carenderia.index');
        Route::post('/upload', [CarenderiaController::class, 'upload'])->middleware(['auth', 'permission:upload_file'])->name('carenderia.upload');

    });

    Route::prefix('loan')->group(function () {
        Route::get('/', [LoanController::class, 'index'])->name('loan.index');
        Route::post('/upload', [LoanController::class, 'upload'])->middleware(['auth', 'permission:upload_file'])->name('loan.upload');

    });

    Route::prefix('consumer-balances')->group(function () {
        Route::get('/', [ConsumerBalanceController::class, 'index'])->name('consumer-balances.index');
        Route::post('/upload', [ConsumerBalanceController::class, 'upload'])->middleware(['auth', 'permission:upload_file'])->name('consumer-balances.upload');
    });

    Route::prefix('grocery')->group(function () {
        Route::get('/', [GroceryController::class, 'index'])->name('grocery.index');
        Route::post('/upload', [GroceryController::class, 'upload'])->middleware(['auth', 'permission:upload_file'])->name('grocery.upload');

    });

    Route::prefix('payments')->group(function () {
        Route::get('/', [PaymentsController::class, 'index'])->name('payments.index');
        Route::post('/upload', [PaymentsController::class, 'upload'])->middleware(['auth', 'permission:upload_file'])->name('payments.upload');

    });

    Route::prefix('users')->middleware(['auth', 'permission:manage_users'])->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('users.index');

        Route::post('/employee', [UserController::class, 'storeEmployee'])->name('users.store.employee');
        Route::post('/admin', [UserController::class, 'storeAdmin'])->name('users.store.admin');
        Route::put('/{user}/permissions', [UserController::class, 'updatePermissions'])->name('users.permissions.update');
    });

    Route::prefix('employee-balances')->group(function () {
        Route::post('/import', [EmployeeBalanceController::class, 'import'])->middleware(['auth', 'permission:upload_file'])->name('balances.import');
        Route::post('/search', [EmployeeBalanceController::class, 'search'])->name('balances.search');
    });
});
