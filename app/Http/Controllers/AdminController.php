<?php

namespace App\Http\Controllers;

use App\Models\Gempa;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    public function dashboard() {
        $total = Gempa::count();
        $latest = Gempa::latest()->take(5)->get();
        return view('admin.dashboard', compact('total','latest'));
    }

    public function gempa() {
        $data = Gempa::latest()->paginate(10);
        return view('admin.gempa', compact('data'));
    }

    // =========================
    // 🔥 DEC LIST
    // =========================
    public function decList() {
        $data = Gempa::latest()->paginate(10);
        return view('admin.dec_list', compact('data'));
    }
}