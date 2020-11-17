<?php

declare(strict_types=1);

namespace Romanpravda\Scormpackager\Schemas;

class Metadata2004Edition4Schema extends AbstractMetadataSchema
{
    /**
     * Returns schema of metadata file
     *
     * @param string $title
     * @param string $entryIdentifier
     * @param string $catalogValue
     * @param string $lifeCycleVersion
     * @param string $classification
     * @return array[]
     */
    public static function getSchema(string $title, string $entryIdentifier, string $catalogValue, string $lifeCycleVersion, string $classification): array
    {
        return [
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
                                "name" => "identifier",
                                "childs" => [
                                    [
                                        "name" => "catalog",
                                        "value" => $catalogValue
                                    ],
                                    [
                                        "name" => "entry",
                                        "value" => $entryIdentifier
                                    ],
                                ],
                            ],
                            [
                                "name" => "title",
                                "childs" => [
                                    [
                                        "name" => "string",
                                        "attributes" => [
                                            "language" => "en-US"
                                        ],
                                        "value" => $title
                                    ],
                                ],
                            ],
                            [
                                "name" => "language",
                                "value" => "en"
                            ],
                            [
                                "name" => "description",
                                "childs" => [
                                    [
                                        "name" => "string",
                                        "attributes" => [
                                            "language" => "en-US"
                                        ],
                                    ],
                                ],
                            ],
                            [
                                "name" => "keyword",
                                "childs" => [
                                    [
                                        "name" => "string",
                                        "attributes" => [
                                            "language" => "en-US"
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    [
                        "name" => "lifeCycle",
                        "childs" => [
                            [
                                "name" => "version",
                                "childs" => [
                                    [
                                        "name" => "string",
                                        "attributes" => [
                                            "language" => "en-US"
                                        ],
                                        "value" => $lifeCycleVersion
                                    ],
                                ],
                            ],
                            [
                                "name" => "status",
                                "childs" => [
                                    [
                                        "name" => "source",
                                        "value" => "LOMv1.0"
                                    ],
                                    [
                                        "name" => "value",
                                        "value" => "final"
                                    ],
                                ],
                            ],
                        ],
                    ],
                    [
                        "name" => "metaMetadata",
                        "childs" => [
                            [
                                "name" => "metadataSchema",
                                "value" => "LOMv1.0"
                            ],
                            [
                                "name" => "metadataSchema",
                                "value" => "SCORM_CAM_v1.3"
                            ],
                        ],
                    ],
                    [
                        "name" => "technical",
                        "childs" => [
                            [
                                "name" => "format",
                                "value" => "text/html"
                            ],
                        ],
                    ],
                    [
                        "name" => "rights",
                        "childs" => [
                            [
                                "name" => "cost",
                                "childs" => [
                                    [
                                        "name" => "source",
                                        "value" => "LOMv1.0"
                                    ],
                                    [
                                        "name" => "value",
                                        "value" => "yes"
                                    ],
                                ],
                            ],
                            [
                                "name" => "copyrightAndOtherRestrictions",
                                "childs" => [
                                    [
                                        "name" => "source",
                                        "value" => "LOMv1.0"
                                    ],
                                    [
                                        "name" => "value",
                                        "value" => "yes"
                                    ],
                                ],
                            ],
                        ],
                    ],
                    [
                        "name" => "classification",
                        "childs" => [
                            [
                                "name" => "purpose",
                                "childs" => [
                                    [
                                        "name" => "source",
                                        "value" => "LOMv1.0"
                                    ],
                                    [
                                        "name" => "value",
                                        "value" => $classification
                                    ],
                                ],
                            ],
                            [
                                "name" => "description",
                                "childs" => [
                                    [
                                        "name" => "string",
                                        "attributes" => [
                                            "language" => "en-US"
                                        ],
                                    ],
                                ],
                            ],
                            [
                                "name" => "keyword",
                                "childs" => [
                                    [
                                        "name" => "string",
                                        "attributes" => [
                                            "language" => "en-US"
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
}
