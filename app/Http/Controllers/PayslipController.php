<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PayslipController extends Controller
{
    public function show($filename)
    {
        $path = storage_path('app/excel_templates/' . $filename);
        return response()->file($path);
    }
}