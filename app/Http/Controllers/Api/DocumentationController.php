<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DocumentationController extends Controller
{
    /**
     * Display API documentation page
     */
    public function index()
    {
        return view('api-documentation');
    }
}
