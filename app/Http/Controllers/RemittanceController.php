<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;


class RemittanceController extends Controller
{
    public function download($filename)
    {
        $filePath = storage_path('app/hdmf_reports/' . $filename);

        if (!file_exists($filePath)) {
            abort(404, 'File not found.');
        }

        return response()->download($filePath)->deleteFileAfterSend(false);
    }
}
