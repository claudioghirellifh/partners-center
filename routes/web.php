<?php

use App\Http\Controllers\AdminRoot\AuthController;
use App\Http\Controllers\AdminRoot\DashboardController;
use App\Http\Controllers\AdminRoot\CompanyController;
use App\Http\Controllers\AdminRoot\ImpersonationController;
use App\Http\Controllers\AdminRoot\PlanController;
use App\Http\Controllers\AdminRoot\IntegrationController;
use App\Http\Controllers\Admin\AuthController as CompanyAuthController;
use App\Http\Controllers\Admin\DashboardController as CompanyDashboardController;
use App\Http\Controllers\Admin\AdminUserController as CompanyAdminUserController;
use App\Http\Controllers\Admin\ProjectController as CompanyProjectController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminRoot\UserController;

Route::get('/', function () {
    return view('welcome');
});

$adminRootPrefix = trim(config('adminroot.path'), '/');

Route::prefix($adminRootPrefix)
    ->name('adminroot.')
    ->group(function (): void {
        Route::get('/', [AuthController::class, 'index'])->name('login.form');
        Route::post('/login', [AuthController::class, 'store'])->name('login');

        Route::middleware('auth.root')->group(function (): void {
            Route::get('/dashboard', DashboardController::class)->name('dashboard');
            Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');

            Route::resource('companies', CompanyController::class)->except(['show']);
            Route::resource('users', UserController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
            Route::resource('plans', PlanController::class)->except(['show']);
            Route::post('/plans/sync-iugu', [PlanController::class, 'syncFromIugu'])->name('plans.sync');

            Route::get('/integrations', [IntegrationController::class, 'index'])->name('integrations.index');
            Route::post('/integrations/iugu', [IntegrationController::class, 'updateIugu'])->name('integrations.iugu.update');

            Route::post('/companies/{company}/impersonate', [ImpersonationController::class, 'store'])
                ->name('companies.impersonate');
            Route::post('/impersonation/leave', [ImpersonationController::class, 'destroy'])
                ->name('impersonation.leave');
        });
    });

// Admin (por empresa, resolvido por URI)
Route::prefix('{company:uri}')
    ->scopeBindings()
    ->middleware('tenant')
    ->group(function (): void {
        Route::get('/', [CompanyAuthController::class, 'index'])->name('company.landing');

        Route::prefix('admin')
            ->name('admin.')
            ->group(function (): void {
                Route::get('/login', [CompanyAuthController::class, 'index'])->name('login.form');
                Route::post('/login', [CompanyAuthController::class, 'store'])->name('login');

                // Password reset (public within tenant)
                Route::get('/forgot-password', [\App\Http\Controllers\Admin\PasswordResetController::class, 'request'])->name('password.request');
                Route::post('/forgot-password', [\App\Http\Controllers\Admin\PasswordResetController::class, 'email'])->name('password.email');
                Route::get('/reset-password/{token}', [\App\Http\Controllers\Admin\PasswordResetController::class, 'resetForm'])->name('password.reset');
                Route::post('/reset-password', [\App\Http\Controllers\Admin\PasswordResetController::class, 'reset'])->name('password.update');

                Route::middleware('auth.company')->group(function (): void {
                    Route::get('/dashboard', CompanyDashboardController::class)->name('dashboard');
                    Route::post('/logout', [CompanyAuthController::class, 'destroy'])->name('logout');

                    Route::resource('admins', CompanyAdminUserController::class)
                        ->except(['show', 'destroy'])
                        ->parameters(['admins' => 'adminUser']);

                    Route::resource('projects', CompanyProjectController::class)
                        ->except(['show'])
                        ->parameters(['projects' => 'project']);

                    Route::resource('customers', \App\Http\Controllers\Admin\CustomerController::class)
                        ->except(['show'])
                        ->parameters(['customers' => 'customer']);
                    Route::get('customers/{customer}/invoices', [\App\Http\Controllers\Admin\CustomerController::class, 'invoices'])
                        ->name('customers.invoices');
                });
            });
    });
