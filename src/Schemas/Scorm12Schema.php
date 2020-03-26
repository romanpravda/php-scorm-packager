<?php

declare(strict_types=1);

namespace Romanpravda\Scormpackager\Schemas;

class Scorm12Schema extends AbstractScormSchema
{
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
     *
     * @return array
     */
    public static function getSchema(string $title, string $identifier, string $organization, string $version, int $masteryScore, string $startingPage, string $pathToDirectory): array
    {
        $schemaIdentifier = self::getSchemaIdentifier($identifier);
        $itemIdentifier = self::getItemIdentifier($identifier);
        $identifierRef = self::getIdentifierRef($identifier);
        $schemaOrganization = self::getSchemaOrganization($organization);

        return [
            [
                "name" => "manifest",
                "attributes" => [
                    "identifier" => $schemaIdentifier,
                    "version" => 1,
                    "xmlns:adlcp" => "http://www.adlnet.org/xsd/adlcp_rootv1p2",
                    "xmlns" => "http://www.imsproject.org/xsd/imscp_rootv1p1p2",
                    "xmlns:xsi" => "http://www.w3.org/2001/XMLSchema-instance",
                    "xsi:schemaLocation" =>
                        "http://www.imsproject.org/xsd/imscp_rootv1p1p2 definitionFiles/imscp_rootv1p1p2.xsd " .
                        "http://www.imsglobal.org/xsd/imsmd_rootv1p2p1 definitionFiles/imsmd_rootv1p2p1.xsd " .
                        "http://www.adlnet.org/xsd/adlcp_rootv1p2 definitionFiles/adlcp_rootv1p2.xsd"
                    ,
                ],
                "childs" => [
                    [
                        "name" => "metadata",
                        "childs" => [
                            [
                                "name" => "schema",
                                "value" => "ADL SCORM",
                            ],
                            [
                                "name" => "schemaversion",
                                "value" => $version,
                            ],
                        ],
                    ],
                    [
                        "name" => "organizations",
                        "attributes" => [
                            "default" => $schemaOrganization,
                        ],
                        "childs" => [
                            [
                                "name" => "organization",
                                "attributes" => [
                                    "identifier" => $schemaOrganization,
                                ],
                                "childs" => [
                                    [
                                        "name" => "title",
                                        "value" => $title,
                                    ],
                                    [
                                        "name" => "item",
                                        "attributes" => [
                                            "identifier" => $itemIdentifier,
                                            "identifierref" => $identifierRef,
                                        ],
                                        "childs" => [
                                            [
                                                "name" => "title",
                                                "value" => $title,
                                            ],
                                            [
                                                "name" => "adlcp:masteryscore",
                                                "value" => $masteryScore,
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    [
                        "name" => "resources",
                        "childs" => [
                            [
                                "name" => "resource",
                                "attributes" => [
                                    "identifier" => $identifierRef,
                                    "type" => "webcontent",
                                    "href" => $startingPage,
                                    "adlcp:scormType" => "sco",
                                ],
                                "childs" => self::getFilesForSchema($pathToDirectory),
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
}