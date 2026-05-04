<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class LauncherController extends Controller
{
    public function download(): BinaryFileResponse
    {
        $path = storage_path('app/public/launcher/NebulaLauncher.exe');

        if (!file_exists($path)) {
            $dir = storage_path('app/public/launcher');
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
            file_put_contents($path, '');
        }

        return response()->download($path, 'NebulaLauncher.exe', [
            'Content-Type' => 'application/octet-stream',
        ]);
    }
}
