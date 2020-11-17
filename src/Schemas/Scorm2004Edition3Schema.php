<?php

declare(strict_types=1);

namespace Romanpravda\Scormpackager\Schemas;

class Scorm2004Edition3Schema extends AbstractScormSchema
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
     * @param string $metadataDescription
     *
     * @return array
     */
    public static function getSchema(string $title, string $identifier, string $organization, string $version, int $masteryScore, string $startingPage, string $pathToDirectory, string $metadataDescription): array
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
                    "xmlns:adlnav" => "http://www.adlnet.org/xsd/adlnav_v1p3",
                    "xmlns" => "http://www.imsglobal.org/xsd/imscp_v1p1",
                    "xmlns:adlseq" => "http://www.adlnet.org/xsd/adlseq_v1p3",
                    "xmlns:imsss" => "http://www.imsglobal.org/xsd/imsss",
                    "xmlns:adlcp" => "http://www.adlnet.org/xsd/adlcp_v1p3",
                    "xmlns:xsi" => "http://www.w3.org/2001/XMLSchema-instance",
                    "xsi:schemaLocation" =>
                        "http://www.imsglobal.org/xsd/imscp_v1p1 definitionFiles/imscp_v1p1.xsd " .
                        "http://www.adlnet.org/xsd/adlcp_v1p3 definitionFiles/adlcp_v1p3.xsd " .
                        "http://www.adlnet.org/xsd/adlseq_v1p3 definitionFiles/adlseq_v1p3.xsd " .
                        "http://www.adlnet.org/xsd/adlnav_v1p3 definitionFiles/adlnav_v1p3.xsd " .
                        "http://www.imsglobal.org/xsd/imsss definitionFiles/imsss_v1p0.xsd"
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
                        ]
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
                                                "name" => "imsss:sequencing",
                                                "childs" => [
                                                    [
                                                        "name" => "imsss:objectives",
                                                        "childs" => [
                                                            [
                                                                "name" => "imsss:primaryObjective",
                                                                "attributes" => [
                                                                    "objectiveID" => "PRIMARYOBJ",
                                                                    "satisfiedByMeasure" => "true",
                                                                ],
                                                                "childs" => [
                                                                    [
                                                                        "name" => "imsss:minNormalizedMeasure",
                                                                        "value" => $masteryScore / 100,
                                                                    ],
                                                                ],
                                                            ],
                                                        ],
                                                    ],
                                                    [
                                                        "name" => "imsss:deliveryControls",
                                                        "attributes" => [
                                                            "completionSetByContent" => "true",
                                                            "objectiveSetByContent" => "true",
                                                        ],
                                                    ],
                                                ],
                                            ],
                                        ],
                                    ],
                                    [
                                        "name" => "imsss:sequencing",
                                        "childs" => [
                                            [
                                                "name" => "imsss:controlMode",
                                                "attributes" => [
                                                    "choice" => "true",
                                                    "flow" => "true",
                                                ],
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