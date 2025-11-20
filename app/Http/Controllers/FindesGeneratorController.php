<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FindesGeneratorController extends Controller
{
    public function download($filename)
    {
        $filePath = storage_path('app/findes/' . $filename);

        if (!file_exists($filePath)) {
            abort(404, 'File not found.');
        }else{
            return response()->download($filePath)->deleteFileAfterSend(false);
        }

    }
}
