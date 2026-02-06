<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Surge\EmailCms\Http\Controllers\Admin\EmailTemplateCrudController;
use Surge\EmailCms\Mail\DynamicEmail;
use Surge\EmailCms\Mail\GeneralEmail;
use Surge\EmailCms\Models\EmailTemplate;

Route::group([
       'prefix' => config('backpack.base.route_prefix', 'admin'),
        'middleware' => array_merge(
            (array) config('backpack.base.web_middleware', 'web'),
            (array) config('backpack.base.middleware_key', 'admin'),
            ['web', backpack_middleware()],
        ),
        'namespace'  => 'Surge\EmailCms\Http\Controllers\Admin',
], function () {
    Route::get('email-template/{id}/test-email', 'EmailTemplateCrudController@testEmail')->name('email-template.test-email');
    Route::crud('email-template', EmailTemplateCrudController::class);

    Route::get('email-template/{id}/send-test', function ($id) {
        $template = EmailTemplate::findOrFail($id);
        $placeholders = collect($template->placeholders ?? [])->pluck('example', 'key')->toArray();
        $to = Auth::user()->email;

        Mail::to($to)->queue(new GeneralEmail($template, $placeholders, $to));
        \Alert::success('Email sent successfully')->flash();
        return redirect()->back();
    });
});


