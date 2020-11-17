<?php

declare(strict_types=1);

namespace Romanpravda\Scormpackager\Schemas;

abstract class AbstractMetadataSchema
{
    /**
     * Returns schema of Metadata
     *
     * @param string $title
     * @param string $entryIdentifier
     * @param string $catalogValue
     * @param string $lifeCycleVersion
     * @param string $classification
     * @return array
     */
    abstract public static function getSchema(string $title, string $entryIdentifier, string $catalogValue, string $lifeCycleVersion, string $classification): array;
}