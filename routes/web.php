<?php

use App\Http\Controllers\ProjectController;
use App\Http\Controllers\IssueController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('projects.index'));

// Rrugët që kërkojnë login
Route::middleware('auth')->group(function () {
    Route::resource('projects', ProjectController::class);

    Route::post('issues/{issue}/tags', [IssueController::class, 'attachTag'])->name('issues.tags.attach');
    Route::delete('issues/{issue}/tags/{tag}', [IssueController::class, 'detachTag'])->name('issues.tags.detach');
    Route::get('issues/{issue}/comments', [IssueController::class, 'comments'])->name('issues.comments.index');
    Route::post('issues/{issue}/comments', [IssueController::class, 'addComment'])->name('issues.comments.store');

    Route::resource('issues', IssueController::class);
});

require __DIR__.'/auth.php';