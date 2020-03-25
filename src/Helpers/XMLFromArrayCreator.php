<?php

declare(strict_types=1);

namespace Romanpravda\Scormpackager\Helpers;

use Romanpravda\Scormpackager\Exceptions\ScormManifestSchemaIsNotValidException;
use SimpleXMLElement;
use Throwable;

class XMLFromArrayCreator
{
    /**
     * Creates XML with SCORM manifest from schema in array
     *
     * @param array $schema
     *
     * @return SimpleXMLElement
     *
     * @throws Throwable
     */
    public static function createManifestXMLFromSchema(array $schema): SimpleXMLElement
    {
        throw_if(!isset($schema['name']), ScormManifestSchemaIsNotValidException::class);

        $elementName = $schema['name'];
        $elementAttributes = $schema['attributes'] ?? [];
        $elementChilds = $schema['childs'] ?? [];
        $elementValue = $schema['value'] ?? '';


        if (empty($elementChilds)) {
            $root = new SimpleXMLElement("<{$elementName}>{$elementValue}</{$elementName}>");
        } else {
            $root = new SimpleXMLElement("<{$elementName}></{$elementName}>");

            self::addChildElementsToElement($root, $elementChilds);
        }

        foreach ($elementAttributes as $elementAttributeName => $elementAttributeValue) {
            $root->addAttribute($elementAttributeName, $elementAttributeValue);
        }

        return $root;
    }

    /**
     * Added child elements to parent
     *
     * @param SimpleXMLElement $parent
     * @param array $childs
     *
     * @throws Throwable
     */
    private static function addChildElementsToElement(SimpleXMLElement $parent, array $childs)
    {
        foreach ($childs as $child) {
            throw_if(!isset($child['name']), ScormManifestSchemaIsNotValidException::class);

            $elementName = $child['name'];
            $elementAttributes = $child['attributes'] ?? [];
            $elementChilds = $child['childs'] ?? [];
            $elementValue = $child['value'] ?? '';

            if (empty($elementChilds)) {
                $childElement = $parent->addChild($elementName, $elementValue);
            } else {
                $childElement = $parent->addChild($elementName);

                self::addChildElementsToElement($childElement, $elementChilds);
            }

            foreach ($elementAttributes as $elementAttributeName => $elementAttributeValue) {
                $childElement->addAttribute($elementAttributeName, $elementAttributeValue);
            }
        }
    }
}