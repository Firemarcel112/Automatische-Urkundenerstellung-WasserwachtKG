<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {

        $certificate_templates = Certificate::paginate(20);

        return view('home', compact('certificate_templates'));
    }
}
