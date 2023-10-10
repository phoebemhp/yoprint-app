<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CleanUploadedFiles
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $files = $request->file();

        foreach ($files as $key => $file) {
            if ($file->isValid()) {
                $content = file_get_contents($file->getRealPath());

                // Clean the content by converting it to UTF-8 encoding
                $cleanedContent = mb_convert_encoding($content, 'UTF-8', 'UTF-8');

                // Save the cleaned content back to the file
                file_put_contents($file->getRealPath(), $cleanedContent);
            }
        }

        return $next($request);
    }
}
