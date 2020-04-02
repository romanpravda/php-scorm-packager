<?php

declare(strict_types=1);

namespace Romanpravda\Scormpackager\Helpers;

use Exception;
use Throwable;
use ZipArchive;

class ZipArchiveHelper
{
    /**
     * Create zip archive from source
     *
     * @param string $pathToDirectory
     * @param string $pathForZipArchive
     *
     * @return string
     *
     * @throws Throwable
     */
    public static function createFromDirectory(string $pathToDirectory, string $pathForZipArchive): string
    {
        ensure_directory($pathForZipArchive);

        $filename = get_random_string().".zip";
        $pathToFile = "{$pathForZipArchive}/{$filename}";

        $zip = new ZipArchive();
        
        throw_if($zip->open($pathToFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true, new Exception("Can not create zip-archive."));

        self::addDirectoriesAndFilesToArchive($zip, $pathToDirectory);

        $zip->close();

        return $pathToFile;
    }

    /**
     * Add files from directory to zip archive
     *
     * @param ZipArchive $zipArchive
     * @param string $pathToDirectory
     */
    private static function addDirectoriesAndFilesToArchive(ZipArchive $zipArchive, string $pathToDirectory)
    {
        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($pathToDirectory, \RecursiveDirectoryIterator::SKIP_DOTS), \RecursiveIteratorIterator::LEAVES_ONLY);

        foreach ($files as $name => $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($pathToDirectory) + 1);

                $zipArchive->addFile($filePath, $relativePath);
            }
        }
    }
}