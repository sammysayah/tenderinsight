<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\CompanyDocumentController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\CsmldocController;
use App\Http\Controllers\CsmlbusiController;
use App\Http\Controllers\ViewingController;
use App\Http\Controllers\ProfileController;

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

// Welcome Page
// Route::get('/', function () {
//     return view('welcome');
// });


Route::get('/', function () {
    return redirect()->route('login');  // Redirect to /custom-login
});
// Authentication Routes with Email Verification
Auth::routes(['verify' => true]); // Enable built-in email verification routes

// Routes for Verified and Approved Users
Route::middleware(['auth', 'verified', 'approved'])->group(function () {
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard'); // User dashboard
});

// Admin Routes for Company Documents (Only accessible by Admins)
Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    // Resource route for managing company documents


    // Admin User Management
    Route::get('/admin/users', [AdminController::class, 'index'])->name('admin.users.index'); // View all users
    Route::post('/admin/users/{user}/approve', [AdminController::class, 'approve'])->name('admin.users.approve'); // Approve a user
});

// Optional Route for Home Redirection
Route::get('/home', function () {
    return redirect('/dashboard');
})->middleware(['auth', 'verified', 'approved']); // Redirect "home" to the dashboard for verified and approved users





use App\Http\Controllers\DashboardController;

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');







Route::middleware(['auth', 'verified', 'approved'])->group(function () {

    // Approve User
    Route::get('/admin/users/approve', [UserManagementController::class, 'showPendingApprovals'])->name('admin.users.approve');
    Route::post('/admin/users/approve/{user}', [UserManagementController::class, 'approve'])->name('admin.users.approve.action');
    
    // Activate User
    Route::get('/admin/users/activate', [UserManagementController::class, 'showInactiveUsers'])->name('admin.users.activate');
    Route::post('/admin/users/activate/{user}', [UserManagementController::class, 'activate'])->name('admin.users.activate.action');

    // Deactivate User
    Route::get('/admin/users/deactivate', [UserManagementController::class, 'showActiveUsers'])->name('admin.users.deactivate');
    Route::post('/admin/users/deactivate/{user}', [UserManagementController::class, 'deactivate'])->name('admin.users.deactivate.action');

    // Edit User (View and Update)
    Route::get('/admin/users/edit', [UserManagementController::class, 'listUsers'])->name('admin.users.edit.list');
    Route::get('/admin/users/edit/{user}', [UserManagementController::class, 'edit'])->name('admin.users.edit');
    Route::put('/admin/users/edit/{user}', [UserManagementController::class, 'update'])->name('admin.users.edit.action');


    // Delete User (show all users page)
    Route::get('/admin/users/delete', [UserManagementController::class, 'showAllUsers'])->name('admin.users.delete');

      // Route for deleting a user
      Route::delete('/admin/users/delete/{user}', [UserManagementController::class, 'delete'])->name('admin.users.delete.action');
  

});









Route::middleware(['auth', 'verified', 'approved'])->prefix('admin')->group(function () {
    Route::get('csmldoc/create', [CsmldocController::class, 'create'])->name('admin.csmldoc.create');
    Route::post('csmldoc/store', [CsmldocController::class, 'store'])->name('admin.csmldoc.store');
    Route::get('csmldoc/{id}/edit', [CsmldocController::class, 'edit'])->name('admin.csmldoc.edit');
    // Route::put('csmldoc/{id}', [CsmldocController::class, 'update'])->name('admin.csmldoc.update');
     // List all documents
   Route::get('csmldoc', [CsmldocController::class, 'index'])->name('admin.csmldoc.index');
   
   Route::get('csmldoc/search', [CsmldocController::class, 'search'])->name('admin.csmldoc.search');

   Route::delete('csmldoc/{id}', [CsmldocController::class, 'destroy'])->name('csmldoc.destroy');

   Route::put('csmldoc/{id}', [CsmldocController::class, 'update'])->name('csmldoc.update');

   
});





Route::middleware(['auth', 'verified', 'approved'])->prefix('admin')->group(function () {
    Route::get('csmlbusi/create', [CsmlbusiController::class, 'create'])->name('admin.csmlbusi.create');
    Route::post('csmlbusi/store', [CsmlbusiController::class, 'store'])->name('admin.csmlbusi.store');
    Route::get('csmlbusi/{id}/edit', [CsmlbusiController::class, 'edit'])->name('admin.csmlbusi.edit');
    Route::put('csmlbusi/{id}', [CsmlbusiController::class, 'update'])->name('admin.csmlbusi.update');
    Route::get('csmlbusi', [CsmlbusiController::class, 'index'])->name('admin.csmlbusi.index');
    // Route::delete('csmlbusi/{id}', [CsmlbusiController::class, 'destroy'])->name('admin.csmlbusi.destroy');
    Route::delete('/csmlbusi/{id}/delete-document', [CsmlbusiController::class, 'deleteDocument'])->name('admin.csmlbusi.deleteDocument');
});


Route::middleware(['auth', 'verified', 'approved'])->prefix('admin')->group(function () {




Route::get('/csmldocs', [ViewingController::class, 'viewCsmldocs'])->name('admin.csmldocs');
Route::get('/csmldoc/search', [ViewingController::class, 'searchCsmldocs']);
Route::get('/csmlbus', [ViewingController::class, 'viewCsmlbusis'])->name('admin.csmlbus');


Route::get('/csmldoc/{id}/download', [CsmldocController::class, 'download'])->name('admin.csmldoc.download');



 Route::get('/csmlbus/download/{businessId}', [ViewingController::class, 'downloadDocuments'])->name('admin.csmldoc.download');



 Route::get('/settings/profile', [ProfileController::class, 'editProfile'])->name('profile.edit');
 Route::post('/settings/profile', [ProfileController::class, 'updateProfile'])->name('profile.update');

 // Change password
 Route::get('/settings/password', [ProfileController::class, 'showChangePasswordForm'])->name('password.change.form');
 Route::post('/settings/password', [ProfileController::class, 'changePassword'])->name('password.change');
});







// Route for downloading a single document
// Route::get('download/csmldoc/{id}', [ViewingController::class, 'download'])->name('download.csmldoc');
// Route::get('csmldoc/download/{id}', [ViewingController::class, 'download'])->name('admin.csmldoc.download');


// // Route::get('csmldoc/download/{id}', [ViewingController::class, 'download'])->name('admin.csmldoc.download');
// Route::get('csmlbusi/download/{businessId}', [ViewingController::class, 'downloadDocuments'])->name('admin.csmlbusi.download');






Route::middleware(['auth','verified', 'approved'])->group(function () {
    // Profile settings
    Route::get('user/settings/profile', [ProfileController::class, 'editProfile'])->name('profile.edit');
    Route::post('user/settings/profile', [ProfileController::class, 'updateProfile'])->name('profile.update');

    // Change password
    Route::get('user/settings/password', [ProfileController::class, 'showChangePasswordForm'])->name('password.change.form');
    Route::post('user/settings/password', [ProfileController::class, 'changePassword'])->name('password.change');
});


//WORKING ROUTES IF IT FAILED WILL USE THIS THAN BELOW

// Route::middleware('auth')->group(function () {
    
//     // Admin Routes (for CSML Docs, CSML Businesses)
//     Route::prefix('admin')->middleware('can:admin')->group(function () {
//         Route::get('csmldocs', [ViewingController::class, 'viewCsmldocs']);
//         Route::get('csmlbus', [ViewingController::class, 'viewCsmlbusis']);
//         Route::get('download/{id}', [ViewingController::class, 'download']);
//         Route::get('download_documents/{businessId}', [ViewingController::class, 'downloadDocuments'])->name('admin.csmlbusi.download');
//     });

//     // User Routes (for CSML Docs, CSML Businesses)
//     Route::prefix('user')->middleware('can:user')->group(function () {
//         Route::get('csmldocs', [ViewingController::class, 'viewCsmldocs']);
//         Route::get('csmlbus', [ViewingController::class, 'viewCsmlbusis']);
//         Route::get('download/{id}', [ViewingController::class, 'download']);
        
//     });
// });

// //allowing everyone to download the document
// Route::get('download_documents/{businessId}', [ViewingController::class, 'downloadDocuments'])
//     ->name('csmlbusi.download');

    //
//WORKING ROUTES IF IT FAILED WILL USE THIS THAN BELOW

Route::middleware('auth')->group(function () {
    // Admin Routes (For CSML Docs, CSML Businesses)
    Route::prefix('admin')->middleware('can:admin')->group(function () {
        Route::get('csmldocs', [ViewingController::class, 'viewCsmldocs']);
        Route::get('csmlbus', [ViewingController::class, 'viewCsmlbusis']);
        Route::get('download/{id}', [ViewingController::class, 'download']);
        Route::get('download_documents/{businessId}', [ViewingController::class, 'downloadDocuments'])
            ->name('admin.csmlbusi.download');
    });

    // **New Route for Users**
    Route::prefix('user')->middleware('can:user')->group(function () {
        Route::get('csmldocs', [ViewingController::class, 'viewCsmldocs']);
        Route::get('csmlbus', [ViewingController::class, 'viewCsmlbusis']);
        Route::get('download/{id}', [ViewingController::class, 'download']);
        Route::get('download_documents/{businessId}', [ViewingController::class, 'downloadDocuments'])
            ->name('user.csmlbusi.download'); // Separate user route
    });
});
