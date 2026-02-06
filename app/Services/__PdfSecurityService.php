<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Smalot\PdfParser\Parser;

class PdfSecurityService
{
    public static function check(UploadedFile $file): void
    {
        // ✅ Only apply to PDF
        if ($file->getClientOriginalExtension() !== 'pdf') {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | 1️⃣ PDF SIGNATURE CHECK
        |--------------------------------------------------------------------------
        */
        $handle = fopen($file->getRealPath(), 'rb');
        $header = fread($handle, 5);
        fclose($handle);

        if ($header !== '%PDF-') {
            abort(422, 'Invalid PDF file');
        }

        /*
        |--------------------------------------------------------------------------
        | 2️⃣ SAFE PARSING
        |--------------------------------------------------------------------------
        */
        try {
            $parser = new Parser();
            $parser->parseFile($file->getRealPath());
        } catch (\Throwable $e) {
            abort(422, 'Invalid or corrupted PDF file');
        }

        /*
        |--------------------------------------------------------------------------
        | 3️⃣ BLOCK EMBEDDED JS / ACTIONS
        |--------------------------------------------------------------------------
        */
        $content = file_get_contents($file->getRealPath());

        $dangerousPatterns = [
            '/\/JavaScript/i',
            '/\/JS/i',
            '/\/OpenAction/i',
            '/\/AA/i',
            '/\/Launch/i',
        ];

        foreach ($dangerousPatterns as $pattern) {
            if (preg_match($pattern, $content)) {
                abort(422, 'PDF contains embedded scripts and is not allowed');
            }
        }
    }
}
