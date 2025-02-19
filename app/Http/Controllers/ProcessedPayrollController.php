<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProcessedPayrollController extends Controller
{
    public function openProcessedPayroll($filename)
    {
        $path = storage_path('app/payrolls/' . $filename . '.pdf');
        return response()->file($path);
    }
}