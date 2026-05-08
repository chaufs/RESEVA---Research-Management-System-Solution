<?php

namespace App\Helpers;

class FileHelper
{
    /**
     * File extensions that can be viewed inline in browsers
     */
    private static $viewableExtensions = [
        // Documents
        'pdf',
        // Images
        'jpg', 'jpeg', 'png', 'gif', 'svg', 'webp', 'bmp', 'ico',
        // Text files
        'txt', 'csv', 'json', 'xml', 'log',
        // Code files
        'html', 'htm', 'css', 'js', 'php', 'py', 'java', 'cpp', 'c', 'cs', 'rb', 'go', 'rs',
        // Video
        'mp4', 'webm', 'ogg', 'avi', 'mov', 'wmv', 'flv', 'm4v',
        // Audio
        'mp3', 'wav', 'ogg', 'aac', 'm4a', 'flac',
        // Other
        'md', 'markdown'
    ];

    /**
     * MIME types that can be viewed inline in browsers
     */
    private static $viewableMimeTypes = [
        'application/pdf',
        'text/plain',
        'text/csv',
        'text/html',
        'text/css',
        'text/javascript',
        'text/json',
        'text/xml',
        'application/json',
        'application/xml',
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/svg+xml',
        'image/webp',
        'image/bmp',
        'image/x-icon',
        'audio/mpeg',
        'audio/wav',
        'audio/ogg',
        'audio/aac',
        'audio/flac',
        'video/mp4',
        'video/webm',
        'video/ogg',
        'video/avi',
        'video/quicktime',
    ];

    /**
     * Check if a file can be viewed inline in the browser
     *
     * @param string $filePath
     * @return bool
     */
    public static function canViewInline($filePath)
    {
        if (!$filePath) {
            return false;
        }

        // Get file extension
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

        // Check if extension is in viewable list
        if (in_array($extension, self::$viewableExtensions)) {
            return true;
        }

        // For files without extensions or unknown extensions, try to get MIME type
        try {
            $mimeType = mime_content_type(storage_path('app/public/' . $filePath));
            return in_array($mimeType, self::$viewableMimeTypes);
        } catch (\Exception $e) {
            // If we can't determine MIME type, assume it's not viewable
            return false;
        }
    }

    /**
     * Get the appropriate icon class for a file type
     *
     * @param string $filePath
     * @return string
     */
    public static function getFileIcon($filePath)
    {
        if (!$filePath) {
            return 'bi bi-file-earmark';
        }

        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

        $iconMap = [
            // Documents
            'pdf' => 'bi bi-file-earmark-pdf',
            'doc' => 'bi bi-file-earmark-word',
            'docx' => 'bi bi-file-earmark-word',
            'xls' => 'bi bi-file-earmark-excel',
            'xlsx' => 'bi bi-file-earmark-excel',
            'ppt' => 'bi bi-file-earmark-ppt',
            'pptx' => 'bi bi-file-earmark-ppt',
            // Images
            'jpg' => 'bi bi-file-earmark-image',
            'jpeg' => 'bi bi-file-earmark-image',
            'png' => 'bi bi-file-earmark-image',
            'gif' => 'bi bi-file-earmark-image',
            'svg' => 'bi bi-file-earmark-image',
            'webp' => 'bi bi-file-earmark-image',
            // Text/Code
            'txt' => 'bi bi-file-earmark-text',
            'csv' => 'bi bi-file-earmark-text',
            'json' => 'bi bi-file-earmark-code',
            'xml' => 'bi bi-file-earmark-code',
            'html' => 'bi bi-file-earmark-code',
            'css' => 'bi bi-file-earmark-code',
            'js' => 'bi bi-file-earmark-code',
            'php' => 'bi bi-file-earmark-code',
            'py' => 'bi bi-file-earmark-code',
            // Video
            'mp4' => 'bi bi-file-earmark-play',
            'webm' => 'bi bi-file-earmark-play',
            'avi' => 'bi bi-file-earmark-play',
            // Audio
            'mp3' => 'bi bi-music-note',
            'wav' => 'bi bi-music-note',
            // Archives
            'zip' => 'bi bi-file-earmark-zip',
            'rar' => 'bi bi-file-earmark-zip',
            '7z' => 'bi bi-file-earmark-zip',
        ];

        return $iconMap[$extension] ?? 'bi bi-file-earmark';
    }

    /**
     * Get human-readable file size
     *
     * @param string $filePath
     * @return string
     */
    public static function getFileSize($filePath)
    {
        if (!$filePath) {
            return '';
        }

        try {
            $bytes = filesize(storage_path('app/public/' . $filePath));

            if ($bytes >= 1073741824) {
                return number_format($bytes / 1073741824, 2) . ' GB';
            } elseif ($bytes >= 1048576) {
                return number_format($bytes / 1048576, 2) . ' MB';
            } elseif ($bytes >= 1024) {
                return number_format($bytes / 1024, 2) . ' KB';
            } else {
                return $bytes . ' bytes';
            }
        } catch (\Exception $e) {
            return '';
        }
    }
}