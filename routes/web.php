<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\TwoFactorController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KnowledgebaseController;
use App\Http\Controllers\RecycleBinController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Settings\CategoryController;
use App\Http\Controllers\Settings\GeneralController;
use App\Http\Controllers\Settings\IntegrationController;
use App\Http\Controllers\Settings\RoleController;
use App\Http\Controllers\Settings\ActivityLogController;
use App\Http\Controllers\Settings\UserManagementController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\ToolController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Guest Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['guest', 'check.banned.ip'])->group(function () {
    // Login
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->middleware('check.banned.email');

    // Register
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->middleware('check.banned.email');

    // Forgot Password
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email')->middleware('check.banned.email');
    
    // Reset Password
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});

// Two-Factor Authentication Verification (after login attempt)
Route::middleware(['check.banned.ip'])->group(function () {
    Route::get('/two-factor/verify', [TwoFactorController::class, 'verify'])->name('two-factor.verify');
    Route::post('/two-factor/verify', [TwoFactorController::class, 'verifyCode'])->name('two-factor.verify.store');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'check.user.status', 'session.timeout'])->group(function () {
    // Logout (POST for form, GET redirects to login)
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/logout', function () {
        return redirect()->route('login');
    });

    // Two-Factor Authentication Management
    Route::get('/two-factor', [TwoFactorController::class, 'show'])->name('two-factor.show');
    Route::post('/two-factor/enable', [TwoFactorController::class, 'enable'])->name('two-factor.enable');
    Route::delete('/two-factor', [TwoFactorController::class, 'disable'])->name('two-factor.disable');
    Route::post('/two-factor/regenerate-codes', [TwoFactorController::class, 'regenerateCodes'])->name('two-factor.regenerate-codes');

    // Dashboard / Overview
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    // Tickets
    Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/create', [TicketController::class, 'create'])->name('tickets.create');
    Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');
    Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show');
    Route::get('/tickets/{ticket}/edit', [TicketController::class, 'edit'])->name('tickets.edit');
    Route::put('/tickets/{ticket}', [TicketController::class, 'update'])->name('tickets.update');
    Route::delete('/tickets/{ticket}', [TicketController::class, 'destroy'])->name('tickets.destroy');
    Route::post('/tickets/{ticket}/reply', [TicketController::class, 'reply'])->name('tickets.reply');
    Route::post('/tickets/{ticket}/assign', [TicketController::class, 'assign'])->name('tickets.assign');
    Route::post('/tickets/{ticket}/update-status', [TicketController::class, 'updateStatus'])->name('tickets.updateStatus');
    Route::post('/tickets/{ticket}/update-priority', [TicketController::class, 'updatePriority'])->name('tickets.updatePriority');
    Route::post('/tickets/{ticket}/restore', [TicketController::class, 'restore'])->name('tickets.restore');
    Route::post('/tickets/{ticket}/force-delete', [TicketController::class, 'forceDelete'])->name('tickets.forceDelete');
    Route::post('/tickets/empty-recycle-bin', [TicketController::class, 'emptyRecycleBin'])->name('tickets.emptyRecycleBin');

    // Knowledgebase
    Route::get('/knowledgebase', [KnowledgebaseController::class, 'index'])->name('knowledgebase.index');
    Route::get('/knowledgebase/settings', [KnowledgebaseController::class, 'settings'])->name('knowledgebase.settings');
    Route::get('/knowledgebase/article/{article}', [KnowledgebaseController::class, 'showArticle'])->name('knowledgebase.article');
    
    // Knowledgebase Articles CRUD
    Route::post('/knowledgebase/articles', [KnowledgebaseController::class, 'storeArticle'])->name('knowledgebase.articles.store');
    Route::get('/knowledgebase/articles/{article}/edit', [KnowledgebaseController::class, 'editArticle'])->name('knowledgebase.articles.edit');
    Route::put('/knowledgebase/articles/{article}', [KnowledgebaseController::class, 'updateArticle'])->name('knowledgebase.articles.update');
    Route::delete('/knowledgebase/articles/{article}', [KnowledgebaseController::class, 'destroyArticle'])->name('knowledgebase.articles.destroy');
    
    // Knowledgebase Categories CRUD
    Route::post('/knowledgebase/categories', [KnowledgebaseController::class, 'storeCategory'])->name('knowledgebase.categories.store');
    Route::put('/knowledgebase/categories/{category}', [KnowledgebaseController::class, 'updateCategory'])->name('knowledgebase.categories.update');
    Route::delete('/knowledgebase/categories/{category}', [KnowledgebaseController::class, 'destroyCategory'])->name('knowledgebase.categories.destroy');

    // Reports
    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::post('/reports/export', [ReportController::class, 'export'])->name('reports.export');

    // Tools
    Route::get('/tools', [ToolController::class, 'index'])->name('tools.index');
    
    // Ban Email
    Route::post('/tools/ban-email', [ToolController::class, 'storeBanEmail'])->name('tools.ban-email.store');
    Route::post('/tools/ban-email/bulk', [ToolController::class, 'bulkBanEmail'])->name('tools.ban-email.bulk');
    Route::put('/tools/ban-email/{bannedEmail}', [ToolController::class, 'updateBanEmail'])->name('tools.ban-email.update');
    Route::delete('/tools/ban-email/{bannedEmail}', [ToolController::class, 'destroyBanEmail'])->name('tools.ban-email.destroy');
    
    // Ban IP
    Route::post('/tools/ban-ip', [ToolController::class, 'storeBanIp'])->name('tools.ban-ip.store');
    Route::post('/tools/ban-ip/bulk', [ToolController::class, 'bulkBanIp'])->name('tools.ban-ip.bulk');
    Route::put('/tools/ban-ip/{bannedIp}', [ToolController::class, 'updateBanIp'])->name('tools.ban-ip.update');
    Route::delete('/tools/ban-ip/{bannedIp}', [ToolController::class, 'destroyBanIp'])->name('tools.ban-ip.destroy');
    
    // Whitelist Email
    Route::post('/tools/whitelist-email', [ToolController::class, 'storeWhitelistEmail'])->name('tools.whitelist-email.store');
    Route::post('/tools/whitelist-email/bulk', [ToolController::class, 'bulkWhitelistEmail'])->name('tools.whitelist-email.bulk');
    Route::put('/tools/whitelist-email/{whitelistEmail}', [ToolController::class, 'updateWhitelistEmail'])->name('tools.whitelist-email.update');
    Route::delete('/tools/whitelist-email/{whitelistEmail}', [ToolController::class, 'destroyWhitelistEmail'])->name('tools.whitelist-email.destroy');
    
    // Whitelist IP
    Route::post('/tools/whitelist-ip', [ToolController::class, 'storeWhitelistIp'])->name('tools.whitelist-ip.store');
    Route::post('/tools/whitelist-ip/bulk', [ToolController::class, 'bulkWhitelistIp'])->name('tools.whitelist-ip.bulk');
    Route::put('/tools/whitelist-ip/{whitelistIp}', [ToolController::class, 'updateWhitelistIp'])->name('tools.whitelist-ip.update');
    Route::delete('/tools/whitelist-ip/{whitelistIp}', [ToolController::class, 'destroyWhitelistIp'])->name('tools.whitelist-ip.destroy');
    
    // Bad Word
    Route::post('/tools/bad-word', [ToolController::class, 'storeBadWord'])->name('tools.bad-word.store');
    Route::post('/tools/bad-word/bulk', [ToolController::class, 'bulkBadWord'])->name('tools.bad-word.bulk');
    Route::put('/tools/bad-word/{badWord}', [ToolController::class, 'updateBadWord'])->name('tools.bad-word.update');
    Route::delete('/tools/bad-word/{badWord}', [ToolController::class, 'destroyBadWord'])->name('tools.bad-word.destroy');
    
    // Bad Website
    Route::post('/tools/bad-website', [ToolController::class, 'storeBadWebsite'])->name('tools.bad-website.store');
    Route::post('/tools/bad-website/bulk', [ToolController::class, 'bulkBadWebsite'])->name('tools.bad-website.bulk');
    Route::put('/tools/bad-website/{badWebsite}', [ToolController::class, 'updateBadWebsite'])->name('tools.bad-website.update');
    Route::delete('/tools/bad-website/{badWebsite}', [ToolController::class, 'destroyBadWebsite'])->name('tools.bad-website.destroy');

    // Tools Export
    Route::get('/tools/export', [ToolController::class, 'export'])->name('tools.export');

    // Platform Settings
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/general', [GeneralController::class, 'index'])->name('general');
        Route::post('/general', [GeneralController::class, 'save'])->name('general.save');
        Route::get('/integrations', [IntegrationController::class, 'index'])->name('integrations');
        Route::post('/integrations/email', [IntegrationController::class, 'saveEmail'])->name('integrations.email.save');
        Route::post('/integrations/email/test', [IntegrationController::class, 'testEmail'])->name('integrations.email.test');
        Route::post('/integrations/telegram', [IntegrationController::class, 'saveTelegram'])->name('integrations.telegram');
        Route::post('/integrations/telegram/test', [IntegrationController::class, 'testTelegram'])->name('integrations.telegram.test');
        Route::post('/integrations/weather', [IntegrationController::class, 'saveWeather'])->name('integrations.weather.save');
        Route::post('/integrations/weather/test', [IntegrationController::class, 'testWeather'])->name('integrations.weather.test');
        Route::post('/integrations/api', [IntegrationController::class, 'saveApi'])->name('integrations.api.save');
        Route::post('/integrations/spam', [IntegrationController::class, 'saveSpam'])->name('integrations.spam.save');
        Route::post('/integrations/spam/test', [IntegrationController::class, 'testSpam'])->name('integrations.spam.test');
        Route::get('/categories', [CategoryController::class, 'index'])->name('categories');
        
        // Categories CRUD
        Route::post('/categories/category', [CategoryController::class, 'storeCategory'])->name('categories.category.store');
        Route::put('/categories/category/{category}', [CategoryController::class, 'updateCategory'])->name('categories.category.update');
        Route::delete('/categories/category/{category}', [CategoryController::class, 'destroyCategory'])->name('categories.category.destroy');
        
        // Priorities CRUD
        Route::post('/categories/priority', [CategoryController::class, 'storePriority'])->name('categories.priority.store');
        Route::put('/categories/priority/{priority}', [CategoryController::class, 'updatePriority'])->name('categories.priority.update');
        Route::delete('/categories/priority/{priority}', [CategoryController::class, 'destroyPriority'])->name('categories.priority.destroy');
        
        // Status CRUD
        Route::post('/categories/status', [CategoryController::class, 'storeStatus'])->name('categories.status.store');
        Route::put('/categories/status/{status}', [CategoryController::class, 'updateStatus'])->name('categories.status.update');
        Route::delete('/categories/status/{status}', [CategoryController::class, 'destroyStatus'])->name('categories.status.destroy');
        
        // SLA Rules CRUD
        Route::post('/categories/sla-rule', [CategoryController::class, 'storeSlaRule'])->name('categories.sla-rule.store');
        Route::put('/categories/sla-rule/{slaRule}', [CategoryController::class, 'updateSlaRule'])->name('categories.sla-rule.update');
        Route::delete('/categories/sla-rule/{slaRule}', [CategoryController::class, 'destroySlaRule'])->name('categories.sla-rule.destroy');
        
        // Email Templates (edit/show only)
        Route::get('/categories/email-template/{emailTemplate}', [CategoryController::class, 'showEmailTemplate'])->name('categories.email-template.show');
        Route::get('/categories/email-template/{emailTemplate}/edit', [CategoryController::class, 'editEmailTemplate'])->name('categories.email-template.edit');
        Route::put('/categories/email-template/{emailTemplate}', [CategoryController::class, 'updateEmailTemplate'])->name('categories.email-template.update');
        
        // Roles
        Route::get('/roles', [RoleController::class, 'index'])->name('roles');
        Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
        Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
        Route::get('/roles/{role}', [RoleController::class, 'show'])->name('roles.show');
        Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
        Route::put('/roles/{role}', [RoleController::class, 'update'])->name('roles.update');
        Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');
        
        // Users
        Route::get('/users', [UserManagementController::class, 'index'])->name('users');
        Route::get('/users/create', [UserManagementController::class, 'create'])->name('users.create');
        Route::post('/users', [UserManagementController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [UserManagementController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserManagementController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');
        Route::post('/users/{user}/lock', [UserManagementController::class, 'lock'])->name('users.lock');
        Route::post('/users/{user}/unlock', [UserManagementController::class, 'unlock'])->name('users.unlock');
        Route::post('/users/{user}/suspend', [UserManagementController::class, 'suspend'])->name('users.suspend');
        Route::post('/users/{user}/unsuspend', [UserManagementController::class, 'unsuspend'])->name('users.unsuspend');
        
        Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs');
        Route::delete('/activity-logs/{activityLog}', [ActivityLogController::class, 'destroy'])->name('activity-logs.destroy');
        Route::delete('/activity-logs', [ActivityLogController::class, 'clear'])->name('activity-logs.clear');
        Route::get('/activity-logs/export', [ActivityLogController::class, 'export'])->name('activity-logs.export');
        Route::get('/audit-logs/export', [ActivityLogController::class, 'exportAudit'])->name('audit-logs.export');
        Route::delete('/audit-logs', [ActivityLogController::class, 'clearAudit'])->name('audit-logs.clear');
    });

    // Recycle Bin Routes
    Route::post('/recycle-bin/{type}/{id}/restore', [RecycleBinController::class, 'restore'])->name('recycle-bin.restore');
    Route::delete('/recycle-bin/{type}/{id}/force-delete', [RecycleBinController::class, 'forceDelete'])->name('recycle-bin.force-delete');
    Route::delete('/recycle-bin/empty-all', [RecycleBinController::class, 'emptyAll'])->name('recycle-bin.empty-all');

    // User routes using hash_link for identification
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
});
