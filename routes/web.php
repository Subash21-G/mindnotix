<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\FormResponseController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SocialLoginController;
use Illuminate\Support\Facades\Auth;

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

// --- PUBLIC & AUTHENTICATION ROUTES ---

Route::get('/', function () {
    // If logged in, go to dashboard. Otherwise, go to login page.
    return Auth::check() ? redirect()->route('dashboard') :
     redirect()->route('login');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register')->middleware('guest');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Social Login Routes
Route::get('/auth/google/redirect', [SocialLoginController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [SocialLoginController::class, 'handleGoogleCallback']);


// --- PUBLIC FORM INTERACTION ROUTES ---
// Anyone can view and submit a form, no login required.

// Renders the public form page for users to fill out


// Handles the submission of a form response
Route::get('/forms/public/{form}', [FormController::class, 'publicShow'])->name('forms.public.show');

Route::post('/forms/{form}/responses', [FormResponseController::class, 'store'])->name('responses.store');


// --- AUTHENTICATED USER ROUTES ---
// All routes in this group require the user to be logged in.

Route::middleware(['auth'])->group(function () {

    // The main dashboard (lists the user's forms)
    Route::get('/dashboard', [FormController::class, 'index'])->name('dashboard');

    // Managing Forms
    Route::get('/forms/create', [FormController::class, 'create'])->name('forms.create'); // Form builder
   Route::get('/forms/{form}', [FormController::class, 'show'])->name('forms.show');
    Route::post('/forms', [FormController::class, 'store'])->name('forms.store');
    Route::get('/forms/{form}/edit',   [FormController::class, 'edit'])->name('forms.edit');
     Route::put('/forms/{form}',        [FormController::class, 'update'])->name('forms.update');
      Route::delete('/forms/{form}',     [FormController::class, 'destroy'])->name('forms.destroy');
    // This route is an alias to the dashboard, good for clarity
    Route::get('/forms', [FormController::class, 'index'])->name('forms.index');

    // Managing Form Responses
    Route::get('/forms/{form}/responses', [FormResponseController::class, 'index'])->name('responses.index');
    Route::delete('forms/{form}/responses/{response}', [FormResponseController::class, 'destroy'])->name('responses.destroy');
    // Managing User Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // For forms
Route::resource('forms', FormController::class);
// For responses



// This provides edit, update, destroy, etc.


});
