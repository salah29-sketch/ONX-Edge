<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminBookingController extends Controller
{
    public function index() { return response()->json([]); }
    public function store(Request $request) { return response()->json([]); }
    public function update(Request $request, $id) { return response()->json([]); }
    public function updateStatus(Request $request, $id) { return response()->json([]); }
}
