<?php

declare(strict_types=1);

namespace Romanpravda\Scormpackager\Schemas;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

abstract class AbstractScormSchema
{
    /**
     * Returns identifier for SCORM manifest
     *
     * @param string $title
     *
     * @return string
     */
    protected static function getSchemaIdentifier(string $title): string
    {
        return str_replace(" ", ".", $title);
    }

    /**
     * Returns identifier for organization's item
     *
     * @param string $identifier
     *
     * @return string
     */
    protected static function getItemIdentifier(string $identifier): string
    {
        return "item_".str_replace(" ", "", $identifier);
    }

    /**
     * Returns identifier referrer for organization's item
     *
     * @param string $identifier
     *
     * @return string
     */
    protected static function getIdentifierRef(string $identifier): string
    {
        return "resource_".str_replace(" ", "", $identifier);
    }

    /**
     * Returns identifier for organization
     *
     * @param string $organization
     *
     * @return string
     */
    protected static function getSchemaOrganization(string $organization): string
    {
        return str_replace(" ", "_", $organization);
    }

    /**
     * Returns file paths for SCORM manifest
     *
     * @param string $pathToDirectory
     * @return array
     */
    protected static function getFilesForSchema(string $pathToDirectory): array
    {
        $filesForSchema = [];
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($pathToDirectory, RecursiveDirectoryIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST);

        foreach ($files as $file) {
            /** @var SplFileInfo $file */
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();

                $relativeFilePath = substr($filePath, strlen($pathToDirectory) + 1);

                $filesForSchema[] = [
                    "name" => "file",
                    "attributes" => [
                        "href" => $relativeFilePath
                    ]
                ];
            }
        }

        return $filesForSchema;
    }

    /**
     * Returns schema of SCORM manifest
     *
     * @param string $title
     * @param string $identifier
     * @param string $organization
     * @param string $version
     * @param int $masteryScore
     * @param string $startingPage
     * @param string $pathToDirectory
     * @param string $metadataDescription
     *
     * @return array
     */
    abstract public static function getSchema(string $title, string $identifier, string $organization, string $version, int $masteryScore, string $startingPage, string $pathToDirectory, string $metadataDescription): array;
}