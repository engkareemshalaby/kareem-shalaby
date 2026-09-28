<?php

use App\Http\Controllers\Admin\ArticleController as AdminArticleController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\ContactMessageController as AdminContactMessageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ExperienceController as AdminExperienceController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\ProjectController as AdminProjectController;
use App\Http\Controllers\Admin\SocialLinkController as AdminSocialLinkController;
use App\Http\Controllers\Admin\TagController as AdminTagController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfessionalProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/ar');
Route::redirect('/articles', '/ar/articles');
Route::get('/articles/{article}', fn (string $article) => redirect("/ar/articles/{$article}"));
Route::redirect('/professional', '/ar/professional');
Route::redirect('/contact', '/ar/contact');

Route::prefix('{locale}')->where(['locale' => 'ar|en'])->middleware('locale')->group(function (): void {
    Route::get('/', HomeController::class)->name('home');
    Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');
    Route::get('/articles/{article}', [ArticleController::class, 'show'])->name('articles.show');
    Route::get('/professional', ProfessionalProfileController::class)->name('professional');
    Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');
    Route::get('/contact', [ContactController::class, 'create'])->name('contact.create');
    Route::post('/contact', [ContactController::class, 'store'])->middleware('throttle:5,1')->name('contact.store');
});
Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');

Route::prefix('admin')->name('admin.')->group(function (): void {
    Route::middleware('guest')->group(function (): void {
        Route::get('/login', [AuthController::class, 'create'])->name('login');
        Route::post('/login', [AuthController::class, 'store'])->middleware('throttle:5,1')->name('login.store');
    });

    Route::middleware(['auth', 'admin'])->group(function (): void {
        Route::get('/', DashboardController::class)->name('dashboard');
        Route::resource('articles', AdminArticleController::class);
        Route::resource('categories', AdminCategoryController::class)->except('show');
        Route::resource('tags', AdminTagController::class)->except('show');
        Route::resource('experiences', AdminExperienceController::class)->except('show');
        Route::resource('projects', AdminProjectController::class);
        Route::resource('social-links', AdminSocialLinkController::class);
        Route::resource('contact-messages', AdminContactMessageController::class)->only(['index', 'show', 'destroy']);
        Route::get('/profile', [AdminProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [AdminProfileController::class, 'update'])->name('profile.update');
        Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');
    });
});
