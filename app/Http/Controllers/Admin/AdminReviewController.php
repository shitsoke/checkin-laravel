<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminReviewController extends Controller
{
    public function index()
    {
        // Add your logic here
        return view('admin.manage_reviews');
    }
}

