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

if (!function_exists('get_random_string')) {
    /**
     * Generate a random string
     *
     * @param int $length
     *
     * @return string
     */
    function get_random_string(int $length = 16): string
    {
        return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', (int) ceil($length/strlen($x)) )), 1, $length);
    }
}

if (!function_exists('delete_directory_if_exists')) {
    /**
     * Delete directory if it exists. Even if it isn't empty.
     *
     * @param string $pathToDirectory
     */
    function delete_directory_if_exists(string $pathToDirectory)
    {
        if (is_dir($pathToDirectory)) {
            $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($pathToDirectory, RecursiveDirectoryIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST);

            foreach ($files as $file) {
                if ($file->isDir()){
                    rmdir($file->getRealPath());
                } else {
                    unlink($file->getRealPath());
                }
            }

            rmdir($pathToDirectory);
        }
    }
}