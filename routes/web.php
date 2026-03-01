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

Route::prefix('admin')->middleware('is.login')->group(function () {
    Route::get('/dashboard', App\Livewire\Admin\Dashboard::class)->name('dashboard');
    Route::get('/documents', App\Livewire\Admin\Documents\Index::class)->name('documents');
    Route::get('/documents/{id}', DocumentDetail::class)->name('documents.show');
    Route::get('/document-out', App\Livewire\Admin\DocumentOut\Index::class)->name('document-out');
    Route::get('/licenses', App\Livewire\Admin\Licenses\Index::class)->name('licenses');
    Route::get('/licenses/{id}', LicenseDetail::class)->name('licenses.show');


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
        Route::get('/document-type', App\Livewire\Admin\Manage\DocumentType\Index::class)->name('document-type');
        Route::get('/action-frequency-unit', App\Livewire\Admin\Manage\ActionFrequencyUnit\Index::class)->name('action-frequency-unit');
    });
});
