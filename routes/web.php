<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReservationController;
use Illuminate\Support\Facades\Route;

// ── Public Catalog (Guest) ────────────────────────────────────────────
Route::get('/', [BookController::class, 'index'])->name('catalog');

// ── Auth ──────────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',   [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register',[AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ── Authenticated ─────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ── Books ── (create/store before {book} to avoid route conflict)
    Route::get('/books',             [BookController::class, 'index'])->name('books.index');
    Route::middleware('admin')->group(function () {
        Route::get('/books/create',      [BookController::class, 'create'])->name('books.create');
        Route::post('/books',            [BookController::class, 'store'])->name('books.store');
        Route::get('/books/{book}/edit', [BookController::class, 'edit'])->name('books.edit');
        Route::put('/books/{book}',      [BookController::class, 'update'])->name('books.update');
        Route::delete('/books/{book}',   [BookController::class, 'destroy'])->name('books.destroy');
    });
    Route::get('/books/{book}', [BookController::class, 'show'])->name('books.show');

    // ── Loans ──
    Route::get('/loans', [LoanController::class, 'index'])->name('loans.index');
    Route::middleware('admin')->group(function () {
        Route::get('/loans/create',              [LoanController::class, 'create'])->name('loans.create');
        Route::post('/loans',                    [LoanController::class, 'store'])->name('loans.store');
        Route::post('/loans/{loan}/return',      [LoanController::class, 'returnBook'])->name('loans.return');
        Route::post('/loans/{loan}/pay-fine',    [LoanController::class, 'payFine'])->name('loans.pay-fine');
    });
    Route::get('/loans/{loan}', [LoanController::class, 'show'])->name('loans.show');

    // ── Reservations ──
    Route::get('/reservations',                           [ReservationController::class, 'index'])->name('reservations.index');
    Route::post('/reservations',                          [ReservationController::class, 'store'])->name('reservations.store');
    Route::post('/reservations/{reservation}/cancel',     [ReservationController::class, 'cancel'])->name('reservations.cancel');

    // ── Profile & Member Card ──
    Route::get('/profile',          [MemberController::class, 'profile'])->name('profile');
    Route::put('/profile',          [MemberController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [MemberController::class, 'updatePassword'])->name('profile.password');
    Route::get('/member-card',      [MemberController::class, 'memberCard'])->name('member.card');

    // ── Admin-only ──
    Route::middleware('admin')->group(function () {

        // Members management
        Route::get('/members',                          [MemberController::class, 'index'])->name('members.index');
        Route::get('/members/{member}',                 [MemberController::class, 'show'])->name('members.show');
        Route::post('/members/{member}/toggle-status',  [MemberController::class, 'toggleStatus'])->name('members.toggle-status');

        // Categories
        Route::get('/categories',              [CategoryController::class, 'index'])->name('categories.index');
        Route::post('/categories',             [CategoryController::class, 'store'])->name('categories.store');
        Route::put('/categories/{category}',   [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}',[CategoryController::class, 'destroy'])->name('categories.destroy');

        // Reports
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    });
});
