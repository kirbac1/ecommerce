<?php

namespace App\Support;

use Illuminate\Contracts\View\View;
use Mpdf\Mpdf;
use Mpdf\Output\Destination;
use Symfony\Component\HttpFoundation\Response;

/**
 * Renders the invoice/receipt/proforma/return views to PDF.
 *
 * Replaces danielboendergaard/phantom-pdf, which shells out to a PhantomJS
 * binary. The copy that ships with the package is a 64-bit Linux ELF, so PDF
 * export failed with "cannot execute binary file" on anything else, and
 * PhantomJS itself was abandoned in 2018. mPDF is pure PHP, so it works
 * wherever the app runs.
 */
class PdfRenderer
{
    /**
     * @param  View|string  $view      the document to render
     * @param  string       $filename  suggested download name
     * @return Response
     */
    public static function createFromView($view, $filename)
    {
        $html = $view instanceof View ? $view->render() : (string) $view;

        $pdf = static::render($html);

        return new Response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Content-Length' => strlen($pdf),
        ]);
    }

    /** Same document, shown in the browser instead of downloaded. */
    public static function inlineFromView($view, $filename)
    {
        $response = static::createFromView($view, $filename);
        $response->headers->set('Content-Disposition', 'inline; filename="' . $filename . '"');

        return $response;
    }

    /** @return string the raw PDF bytes */
    protected static function render($html)
    {
        $tempDir = storage_path('app/mpdf');

        if (! is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $mpdf = new Mpdf([
            'tempDir' => $tempDir,
            'format' => 'A4',
            'margin_top' => 12,
            'margin_bottom' => 12,
            'margin_left' => 12,
            'margin_right' => 12,
            // The documents contain Finnish text.
            'mode' => 'utf-8',
            // Nothing should be fetched remotely; fail fast if anything tries.
            'curlTimeout' => 5,
            'curlFollowLocation' => false,
        ]);

        $mpdf->WriteHTML(static::localiseAssets($html));

        return $mpdf->Output('', Destination::STRING_RETURN);
    }

    /**
     * Resolve the document's assets on disk.
     *
     * The templates reference root-relative URLs ("/templates/assets/css/..."),
     * which mPDF would otherwise try to fetch over HTTP -- blocking until the
     * request times out, or forever. Rewriting them to filesystem paths keeps
     * rendering entirely offline.
     */
    protected static function localiseAssets($html)
    {
        // Scripts do nothing in a PDF and may point at hosts that no longer exist.
        $html = preg_replace('#<script\b[^>]*>.*?</script>#is', '', $html);

        return preg_replace_callback(
            '#\b(src|href)=(["\'])/(?!/)([^"\']*)\2#i',
            function ($match) {
                $path = public_path($match[3]);

                // Drop the reference entirely when the file is not there, so
                // mPDF does not fall back to fetching it.
                return is_file($path)
                    ? $match[1] . '=' . $match[2] . $path . $match[2]
                    : '';
            },
            $html
        );
    }
}
