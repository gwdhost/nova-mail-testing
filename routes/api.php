<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Tool API Routes
|--------------------------------------------------------------------------
|
| Here is where you may register API routes for your tool. These routes
| are loaded by the ServiceProvider of your tool. They are protected
| by your tool's "Authorize" middleware by default. Now, go build!
|
*/

Route::get('/mails', 'Gwdhost\MailTesting\Http\Controllers\MailsController@mails');

Route::post('/send-email', 'Gwdhost\MailTesting\Http\Controllers\MailsController@send_email');
Route::get('/view-email', 'Gwdhost\MailTesting\Http\Controllers\MailsController@view_email');
