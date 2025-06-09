<?php

namespace App\Http\Utils\Traits;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

trait PdfTrait
{
    /**
     * Store a PDF to the given path using current date structure.
     */
    public static function storePDF(string $pdfContent, string $type, string $filename): string
    {
        $datePath = now()->format('Y/m');
        $safeFilename = Str::slug($filename) . '.pdf';
        $path = "{$type}/{$datePath}/{$safeFilename}";

        Storage::disk('public')->put($path, $pdfContent);

        return $path;
    }

    /**
     * Delete a PDF if it exists.
     */
    public static function deletePDF(string $path): void
    {
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    /**
     * Generate a PDF from a Blade template and return as stream/download or save to storage.
     */
    public static function generatePdf(
        string $template,
        string $filename = 'default',
        array $data = [],
        bool $store = false,
        bool $download = false
    ) {
        // Verify template exists
        if (!view()->exists($template)) {
            Log::error("PDF generation failed: View '{$template}' not found.");
            throw new \InvalidArgumentException("View '{$template}' does not exist.");
        }

        try {
            Log::info("Generating PDF from template: {$template}", ['data_keys' => array_keys($data)]);

            $pdf = Pdf::loadView($template, $data);
            $domPdf = $pdf->getDomPDF();
            $canvas = $domPdf->get_canvas();

            // Add page number at bottom right
            $canvas->page_text(500, 800, "Page {PAGE_NUM} of {PAGE_COUNT}", null, 10, [0, 0, 0]);

            $safeFilename = Str::slug($filename) . '_' . time() . '.pdf';

            if ($store) {
                $pdfContent = $pdf->output();
                $path = self::storePDF($pdfContent, 'payslips', $filename);
                Log::info("PDF stored at: {$path}");
                return $path;
            }

            if ($download) {
                return $pdf->download($safeFilename);
            }

            return $pdf->stream($safeFilename);

        } catch (\Exception $e) {
            Log::error("PDF generation failed: " . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            throw $e;
        }
    }
}

