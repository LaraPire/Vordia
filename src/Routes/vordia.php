<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;
use Rayiumir\Vordia\Http\Controllers\Admin\LogoutController;
use Rayiumir\Vordia\Http\Controllers\Auth\Mobile\MobileController;

Route::group(["prefix" => "admin", "middleware" => ["web"]], static function ($router) {

    // Admin

    $router->get("/", [Rayiumir\Vordia\Http\Controllers\Admin\AdminController::class, "index",])->name("admin.index")->middleware("auth");
    // Logout

    $router->get("/logout", LogoutController::class)->name("auth.logout")->middleware("auth");
});

Route::group(['middleware' => 'web'], static function ($router) {
    $router->any("/login", [MobileController::class, "mobile"])->name("login");

    $router->post("/checkOTP", [MobileController::class, "checkOTP"]);

    $router->post("/resendOTP", [MobileController::class, "resendOTP"]);
});
