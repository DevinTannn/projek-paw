<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Kita arahkan ke file view di folder views/admin/dashboard.blade.php
        return view('admin.dashboard');
    }
}