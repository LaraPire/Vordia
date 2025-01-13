<?php

namespace Rayiumir\Vordia\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    public function index()
    {
        return view('Vordia::admin.index');
    }
}
