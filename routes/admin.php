<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminExperienceController;
use App\Http\Controllers\AdminFormationController;
use App\Http\Controllers\AdminProfileController;
use App\Http\Controllers\AdminProjectController;
use App\Http\Controllers\AdminTechnologyController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/messages', [AdminController::class, 'messages'])->name('admin.messages.index');
    Route::get('/admin/messages/{message}', [AdminController::class, 'showMessage'])->name('admin.messages.show');
    Route::patch('/admin/messages/{message}/read', [AdminController::class, 'markMessageAsRead'])->name('admin.messages.read');
    Route::resource('/admin/projects', AdminProjectController::class)
        ->except(['show'])
        ->names('admin.projects');
    Route::resource('/admin/experiences', AdminExperienceController::class)
        ->except(['show'])
        ->names('admin.experiences');
    Route::resource('/admin/formations', AdminFormationController::class)
        ->except(['show'])
        ->names('admin.formations');
    Route::get('/admin/profile', [AdminProfileController::class, 'edit'])->name('admin.profile');
    Route::post('/admin/profile', [AdminProfileController::class, 'store'])->name('admin.profile.store');
    Route::put('/admin/profile', [AdminProfileController::class, 'update'])->name('admin.profile.update');
    Route::delete('/admin/profile', [AdminProfileController::class, 'destroy'])->name('admin.profile.destroy');
    Route::get('/admin/technologies', [AdminTechnologyController::class, 'index'])->name('admin.technologies.index');
    Route::post('/admin/technologies', [AdminTechnologyController::class, 'store'])->name('admin.technologies.store');
    Route::put('/admin/technologies/{competence}', [AdminTechnologyController::class, 'update'])->name('admin.technologies.update');
    Route::delete('/admin/technologies/{competence}', [AdminTechnologyController::class, 'destroy'])->name('admin.technologies.destroy');
});
