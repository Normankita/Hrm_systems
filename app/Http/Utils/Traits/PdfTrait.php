<?php
namespace App\Http\Utils\Traits;

use Barryvdh\DomPDF\Facade\Pdf;
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


    public static function deletePDF(string $path)
    {
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }



    public static function generatePdf($template, $filename = 'default.pdf', $data = [], $downlaod = false)
    {
        $pdf = Pdf::loadView($template, $data);
        $pdf->output();

        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $canvas->page_text(
            500,
            800,
            "Page {PAGE_NUM} of {PAGE_COUNT}",
            null,
            10,
            [0, 0, 0]
        );
        $filename = $filename . time() . '.pdf';

        if ($downlaod) {
            return $pdf->download($filename);
        }
        return $pdf->stream($filename);

    }
}

