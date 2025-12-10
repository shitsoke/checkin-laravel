<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class RoomController extends Controller
{
    public function index()
    {
        // Add your logic here
        return view('admin.rooms.index');
    }
}
