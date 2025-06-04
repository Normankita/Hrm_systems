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


    public static function printPdf(
        $template,
        $data = [],
        $filename = "default",
        $download = false,
        $saveToStorage = false
    ) {
        $pdf = Pdf::loadView($template, $data);

        // Add page numbers
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

        $timestampedFilename = $filename . '_' . time() . '.pdf';

        // Store to local storage
        if ($saveToStorage) {
            if ($saveToStorage === true) {
                $saveToStorage = '';
            }
            try {
                Storage::disk('public')->put('pdfs/' . $saveToStorage . '/' . $timestampedFilename, $pdf->output());
            } catch (\Exception $e) {
                throw new \Exception($e->getMessage());
            }
        }

        // Download or stream
        if ($download) {
            return $pdf->download($timestampedFilename);
        }

        return $pdf->stream($timestampedFilename);
    }
}

