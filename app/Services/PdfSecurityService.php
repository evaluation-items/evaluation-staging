<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Smalot\PdfParser\Parser;

class PdfSecurityService
{
    public static function check(UploadedFile $file, bool $throw = true): bool
    {
        if (strtolower($file->getClientOriginalExtension()) !== 'pdf') {
            return true;
        }

        $path = $file->getRealPath();

        $handle = fopen($path, 'rb');
        $header = fread($handle, 5);
        fclose($handle);

        if ($header !== '%PDF-') {
            return self::fail('Invalid PDF file format', $throw);
        }

        $content = file_get_contents($path, false, null, 0, 2 * 1024 * 1024);

        $patterns = [
            '/\/JavaScript\s*<<|\/JavaScript\s*\(/i',
            '/\/JS\s*<<|\/JS\s*\(/i',
            '/\/OpenAction\s*<<|\/OpenAction\s*\(/i',
            '/\/AA\s*<<|\/AA\s*\(/i',
            '/\/Launch\s*<<|\/Launch\s*\(/i',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $content)) {
                return self::fail('PDF contains embedded scripts and is not allowed', $throw);
            }
        }

        return true;
    }

    protected static function fail(string $message, bool $throw): bool
    {
        if ($throw) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'file' => $message,
            ]);
        }

        return false;
    }
}
