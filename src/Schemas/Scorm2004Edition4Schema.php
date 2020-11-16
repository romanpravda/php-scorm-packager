<?php

declare(strict_types=1);

namespace Romanpravda\Scormpackager\Schemas;

class Scorm2004Edition4Schema extends AbstractScormSchema
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
                    "version" => 1.3, // Скорректировано
                    "xmlns:adlnav" => "http://www.adlnet.org/xsd/adlnav_v1p3",
                    "xmlns:lom" => "http://ltsc.ieee.org/xsd/LOM",
                    "xmlns" => "http://www.imsglobal.org/xsd/imscp_v1p1",
                    "xmlns:adlseq" => "http://www.adlnet.org/xsd/adlseq_v1p3",
                    "xmlns:imsss" => "http://www.imsglobal.org/xsd/imsss",
                    "xmlns:adlcp" => "http://www.adlnet.org/xsd/adlcp_v1p3",
                    "xmlns:xsi" => "http://www.w3.org/2001/XMLSchema-instance",
                    "xsi:schemaLocation" =>
                        "http://www.imsglobal.org/xsd/imscp_v1p1 imscp_v1p1.xsd " .
                        "http://www.adlnet.org/xsd/adlcp_v1p3 adlcp_v1p3.xsd " .
                        "http://www.adlnet.org/xsd/adlseq_v1p3 adlseq_v1p3.xsd " .
                        "http://www.adlnet.org/xsd/adlnav_v1p3 adlnav_v1p3.xsd " .
                        "http://www.imsglobal.org/xsd/imsss imsss_v1p0.xsd " .
                        "http://ltsc.ieee.org/xsd/LOM lom.xsd"
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
                            [
                                "name" => "adlcp:location",
                                "value" => "metadata.xml",
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
                                    "adlseq:objectivesGlobalToSystem" => false,
                                    "structure" => "hierarchical",
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
                                            "isvisible" => true,
                                        ],
                                        "childs" => [
                                            [
                                                "name" => "title",
                                                "value" => $title,
                                            ],
                                            [
                                                "name" => "adlnav:presentation",
                                                "childs" => [
                                                    [
                                                        "name" => "adlnav:navigationInterface",
                                                        "childs" => [
                                                            [
                                                                "name" => "adlnav:hideLMSUI",
                                                                "value" => "continue",
                                                            ],
                                                            [
                                                                "name" => "adlnav:hideLMSUI",
                                                                "value" => "previous",
                                                            ],
                                                            [
                                                                "name" => "adlnav:hideLMSUI",
                                                                "value" => "exit",
                                                            ],
                                                            [
                                                                "name" => "adlnav:hideLMSUI",
                                                                "value" => "exitAll",
                                                            ],
                                                            [
                                                                "name" => "adlnav:hideLMSUI",
                                                                "value" => "abandon",
                                                            ],
                                                            [
                                                                "name" => "adlnav:hideLMSUI",
                                                                "value" => "abandonAll",
                                                            ],
                                                            [
                                                                "name" => "adlnav:hideLMSUI",
                                                                "value" => "suspendAll",
                                                            ],
                                                        ],
                                                    ],
                                                ],
                                            ],
                                            [
                                                "name" => "imsss:sequencing",
                                                "childs" => [
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
                                        "name" => "metadata",
                                        "childs" => [
                                            [
                                                "name" => "lom",
                                                "attributes" => [
                                                    "xmlns" => "http://ltsc.ieee.org/xsd/LOM",
                                                    "xmlns:xsi" => "http://www.w3.org/2001/XMLSchema-instance",
                                                    "xsi:schemaLocation" => "http://ltsc.ieee.org/xsd/LOM lom.xsd",
                                                ],
                                                "childs" => [
                                                    [
                                                        "name" => "general",
                                                        "childs" => [
                                                            [
                                                                "name" => "description",
                                                                "childs" => [
                                                                    [
                                                                        "name" => "string",
                                                                        "attributes" => [
                                                                            "language" => "en"
                                                                        ],
                                                                        "value" => "Провайдер: Synergy Technology; Дата сборки: 19.10.2020; Технология: html; Среда разработки: Course Editor;"
                                                                    ],
                                                                ],
                                                            ],
                                                        ],
                                                    ],
                                                ],
                                            ],
                                            [
                                                "name" => "adlcp:location",
                                                "value" => "metadata.xml",
                                            ],
                                        ],
                                    ],
                                    [
                                        "name" => "imsss:sequencing",
                                        "childs" => [
                                            [
                                                "name" => "imsss:controlMode",
                                                "attributes" => [
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
                                "name" => "metadata",
                                "childs" => [
                                    [
                                        "name" => "lom",
                                        "attributes" => [
                                            "xmlns" => "http://ltsc.ieee.org/xsd/LOM",
                                            "xmlns:xsi" => "http://www.w3.org/2001/XMLSchema-instance",
                                            "xsi:schemaLocation" => "http://ltsc.ieee.org/xsd/LOM lom.xsd",
                                        ],
                                        "childs" => [
                                            [
                                                "name" => "general",
                                                "childs" => [
                                                    [
                                                        "name" => "title",
                                                        "childs" => [
                                                            [
                                                                "name" => "string",
                                                                "attributes" => [
                                                                    "language" => "en"
                                                                ],
                                                                "value" => "Пользователи хотят иметь возможность менять размер ролика, теста и заголовка лонгрида(b_191020)"
                                                            ],
                                                        ],
                                                    ],
                                                    [
                                                        "name" => "description",
                                                        "childs" => [
                                                            [
                                                                "name" => "string",
                                                                "attributes" => [
                                                                    "language" => "en"
                                                                ],
                                                                "value" => "Провайдер: Synergy Technology; Дата сборки: 19.10.2020; Технология: html; Среда разработки: Course Editor;"
                                                            ],
                                                        ],
                                                    ],
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
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
