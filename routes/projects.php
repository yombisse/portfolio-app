<?php

use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

Route::get('/projet', function () {
    return redirect()->route('home', ['#' => 'projects']);
})->name('projects.singular');

Route::get('/projets', function () {
    return redirect()->route('home', ['#' => 'projects']);
})->name('projects.index');

Route::get('/projets/{id}', [ProjectController::class, 'show'])->name('projects.show');
