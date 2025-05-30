<?php 
namespace App\Http\Utils\Traits;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait PdfTrait
{
    public static function storePDF(string $pdfContent, string $type, string $filename): string
    {
        $datePath = now()->format('Y/m');
        $safeFilename = Str::slug($filename) . '.pdf';
        $path = "{$type}/{$datePath}/{$safeFilename}";

        Storage::put($path, $pdfContent);
        return $path;
    }
}

