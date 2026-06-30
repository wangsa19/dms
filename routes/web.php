<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Livewire\Admin\Documents\Show as DocumentDetail;
use App\Livewire\Admin\Licenses\Show as LicenseDetail;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/sign-in', App\Livewire\Auth\SignIn::class)->name('login');
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('logout');

// Rute rahasia untuk mentrigger command dari luar (karena Render free tier tidak ada cron)
// Gunakan layanan gratis seperti cron-job.org untuk mengakses URL ini setiap hari
Route::get('/trigger-cron/{key}', function ($key) {
    // Saran: ganti 'rahasia123' dengan string yang panjang dan acak di production
    if ($key !== 'rahasia123') {
        abort(403, 'Unauthorized');
    }

    $logOutput = "";

    try {
        \Illuminate\Support\Facades\Artisan::call('app:send-license-reminder');
        $logOutput .= "=== SEND LICENSE REMINDER ===\n";
        $logOutput .= \Illuminate\Support\Facades\Artisan::output() . "\n\n";

        \Illuminate\Support\Facades\Artisan::call('app:update-expired-licenses');
        $logOutput .= "=== UPDATE EXPIRED LICENSES ===\n";
        $logOutput .= \Illuminate\Support\Facades\Artisan::output() . "\n\n";

        \Illuminate\Support\Facades\Artisan::call('app:send-document-return-reminder');
        $logOutput .= "=== SEND DOCUMENT RETURN REMINDER ===\n";
        $logOutput .= \Illuminate\Support\Facades\Artisan::output() . "\n";
    } catch (\Throwable $e) {
        $logOutput .= "\n=== ERROR OCCURRED ===\n";
        $logOutput .= "Message: " . $e->getMessage() . "\n";
        $logOutput .= "File: " . $e->getFile() . " (Line: " . $e->getLine() . ")\n";
    }

    // Return response sebagai plain text agar rapi saat dibaca di log cron-job.org
    return response($logOutput)->header('Content-Type', 'text/plain');
});

// Rute Umum untuk user yang sudah login (Staff, Admin, dll)
Route::middleware('is.login')->group(function () {
    // Dashboard dan halaman umum tanpa prefix admin
    Route::get('/dashboard', App\Livewire\Admin\Dashboard::class)->name('dashboard');
    Route::get('/profile', App\Livewire\Admin\Profile\Index::class)->name('profile');
    Route::get('/document-out', App\Livewire\Admin\DocumentOut\Index::class)->name('document-out');
    Route::get('/notifications', \App\Livewire\Admin\Notifications\Index::class)->name('admin.notifications.index');

    // Rute Documents dengan middleware permission
    Route::group(['middleware' => ['permission:view documents']], function () {
        Route::get('/documents', App\Livewire\Admin\Documents\Index::class)->name('documents');
        Route::get('/documents/{document}', DocumentDetail::class)->name('documents.show');
    });

    // Rute Licenses dengan middleware permission
    Route::group(['middleware' => ['permission:view licenses']], function () {
        Route::get('/licenses', App\Livewire\Admin\Licenses\Index::class)->name('licenses');
        Route::get('/licenses/{license}', LicenseDetail::class)->name('licenses.show');
    });

    // Rute Master Data / Manajemen, tetap di bawah admin prefix karena khusus administrator
    Route::prefix('admin')->group(function () {
        Route::prefix('manage')->name('manage.')->group(function () {
            Route::get('/user', App\Livewire\Admin\Manage\User\Index::class)->name('user');
            Route::get('/role', App\Livewire\Admin\Manage\Role\Index::class)->name('role');
            Route::get('/permission', App\Livewire\Admin\Manage\Permission\Index::class)->name('permission');
            Route::get('/employee', App\Livewire\Admin\Manage\Employee\Index::class)->name('employee');
            Route::get('/position', App\Livewire\Admin\Manage\Position\Index::class)->name('position');
            Route::get('/department', App\Livewire\Admin\Manage\Department\Index::class)->name('department');
            Route::get('/category', App\Livewire\Admin\Manage\Category\Index::class)->name('category');
            Route::get('/section', App\Livewire\Admin\Manage\Section\Index::class)->name('section');
            Route::get('/field', App\Livewire\Admin\Manage\Field\Index::class)->name('field');
            Route::get('/racks', App\Livewire\Admin\Manage\Rack\Index::class)->name('racks');
            Route::get('/document-type', App\Livewire\Admin\Manage\DocumentType\Index::class)->name('document-type');
            Route::get('/action-frequency-unit', App\Livewire\Admin\Manage\ActionFrequencyUnit\Index::class)->name('action-frequency-unit');
        });
    });
});