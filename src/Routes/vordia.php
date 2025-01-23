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

Route::group(
    ["prefix" => "admin"],
    static function ($router) {

        // Admin

        $router
            ->get("/", [
                Rayiumir\Vordia\Http\Controllers\Admin\AdminController::class,
                "index",
            ])
            ->name("admin.index");

        // Logout

        $router
            ->get(
                "/logout",
                \Rayiumir\Vordia\Http\Controllers\Admin\LogoutController::class
            )
            ->name("auth.logout");

        $router
            ->any("/login/mobile", [
                \Rayiumir\Vordia\Http\Controllers\Auth\Mobile\MobileController::class,
                "mobile"
            ])
            ->name("auth.mobile");

        $router
            ->post("/check-otp", [
                \Rayiumir\Vordia\Http\Controllers\Auth\Mobile\MobileController::class,
                "checkOTP"
            ]);
    }
);

Route::group(
    [],
    static function ($router) {

        $router
            ->any("/login/mobile", [
                \Rayiumir\Vordia\Http\Controllers\Auth\Mobile\MobileController::class,
                "mobile"
            ])
            ->name("auth.mobile");

        $router
            ->post("/check-otp", [
                \Rayiumir\Vordia\Http\Controllers\Auth\Mobile\MobileController::class,
                "checkOTP"
            ]);
    }
);
