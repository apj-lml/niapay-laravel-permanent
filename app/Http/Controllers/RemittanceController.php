<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;

class RemittanceController extends Controller
{
    public function download($filename)
    {
        if (Str::contains($filename, 'hdmf')) {
            $filePath = storage_path('app/hdmf_reports/' . $filename);
        } elseif (Str::contains($filename, 'gsis')) {
            $filePath = storage_path('app/gsis_reports/' . $filename);
        } elseif (Str::contains($filename, 'whtax')) {
            $filePath = storage_path('app/bir_reports/' . $filename);
        }

        if (!file_exists($filePath)) {
            abort(404, 'File not found.');
        }

        return response()->download($filePath)->deleteFileAfterSend(false);
    }
}
