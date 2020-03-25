<?php

declare(strict_types=1);

if (!function_exists('throw_if')) {
    /**
     * Throw the given exception if the given condition is true.
     *
     * @param  mixed  $condition
     * @param  \Throwable|string  $exception
     * @param  array  ...$parameters
     * @return mixed
     *
     * @throws \Throwable
     */
    function throw_if($condition, $exception, ...$parameters)
    {
        if ($condition) {
            throw (is_string($exception) ? new $exception(...$parameters) : $exception);
        }

        return $condition;
    }
}

if (!function_exists('ensure_directory')) {
    /**
     * Create directory if it doesn't exists
     *
     * @param string $pathToDirectory
     */
    function ensure_directory(string $pathToDirectory)
    {
        if (!is_dir($pathToDirectory)) {
            mkdir($pathToDirectory, 0777, true);
        }
    }
}

if (!function_exists('copy_files')) {
    /**
     * Copy given files from source directory to destination directory
     *
     * @param RecursiveIteratorIterator $files
     * @param string $sourceDirectory
     * @param string $destinationDirectory
     */
    function copy_files(\RecursiveIteratorIterator $files, string $sourceDirectory, string $destinationDirectory)
    {
        foreach ($files as $file) {
            /** @var \SplFileInfo $file */
            $filePath = $file->getRealPath();
            $fileDirectory = $file->getPathInfo()->getPathname();

            $relativeFilePath = substr($filePath, strlen($sourceDirectory) + 1);
            $relativeFileDirectory = substr($fileDirectory, strlen($sourceDirectory));

            $filePathInDestinationDirectory = $destinationDirectory.DIRECTORY_SEPARATOR.$relativeFilePath;
            $fileDirectoryInDestinationDirectory = $destinationDirectory.$relativeFileDirectory;

            ensure_directory($fileDirectoryInDestinationDirectory);
            copy($filePath, $filePathInDestinationDirectory);
        }
    }
}