<?php

declare(strict_types=1);

namespace Romanpravda\Scormpackager;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Romanpravda\Scormpackager\Exceptions\DestinationNotSetException;
use Romanpravda\Scormpackager\Exceptions\IdentifierNotSetException;
use Romanpravda\Scormpackager\Exceptions\SourceNotSetException;
use Romanpravda\Scormpackager\Exceptions\TitleNotSetException;
use Romanpravda\Scormpackager\Exceptions\VersionIsNotSupportedException;
use Romanpravda\Scormpackager\Exceptions\VersionNotSetException;
use Romanpravda\Scormpackager\Helpers\ScormVersions;
use Romanpravda\Scormpackager\Helpers\XMLFromArrayCreator;
use Romanpravda\Scormpackager\Helpers\ZipArchiveHelper;
use Romanpravda\Scormpackager\Schemas\AbstractMetadataSchema;
use Romanpravda\Scormpackager\Schemas\AbstractScormSchema;
use Romanpravda\Scormpackager\Schemas\Metadata2004Edition4Schema;
use Romanpravda\Scormpackager\Schemas\Scorm12Schema;
use Romanpravda\Scormpackager\Schemas\Scorm2004Edition3Schema;
use Romanpravda\Scormpackager\Schemas\Scorm2004Edition4Schema;
use Throwable;

class Packager
{
    const XML_MANIFEST_FILE_NAME = "imsmanifest.xml";
    const DIRECTORY_FOR_DEFINITION_FILES = "definitionFiles";
    const XML_METADATA_FILE_NAME = "metadata.xml";

    /**
     * Version of SCORM package
     *
     * @var string
     */
    private $version;

    /**
     * Organization name for SCORM package
     *
     * @var string
     */
    private $organization;

    /**
     * Course title for SCORM package
     *
     * @var string
     */
    private $title;

    /**
     * Course identifier for SCORM package
     *
     * @var string
     */
    private $identifier;

    /**
     * Passing score for course in SCORM package
     *
     * @var int
     */
    private $masteryScore;

    /**
     * Starting page for SCORM package
     *
     * @var string
     */
    private $startingPage;

    /**
     * Source directory for SCORM package
     *
     * @var string
     */
    private $source;

    /**
     * Destination directory for SCORM package
     *
     * @var string
     */
    private $destination;

    /**
     * Package filename
     *
     * @var string
     */
    private $packageFilename;

    /**
     * Create zip archive after building package
     *
     * @var bool
     */
    private $createZipArchive;

    /**
     * Metadata description
     *
     * @var string
     */
    private $metadataDescription;

    /**
     * Metadata entry identifier
     *
     * @var string
     */
    private $entryIdentifier;

    /**
     * Metadata catalog value
     *
     * @var string
     */
    private $catalogValue;

    /**
     * Metadata lifeCycle version
     *
     * @var string
     */
    private $lifeCycleVersion;

    /**
     * Metadata classification
     *
     * @var string
     */
    private $classification;

    /**
     * Packager constructor.
     *
     * @param array $config
     * @param array|null $metadataConfig
     *
     * @throws Throwable
     */
    public function __construct(array $config, ?array $metadataConfig = [])
    {
        $this->setConfig($config);
        $this->setMetadataConfig($metadataConfig);
    }

    /**
     * Applying config
     *
     * @param array $config
     *
     * @throws Throwable
     */
    private function setConfig(array $config)
    {
        throw_if(!isset($config['title']), TitleNotSetException::class);
        throw_if(!isset($config['identifier']), IdentifierNotSetException::class);
        throw_if(!isset($config['version']), VersionNotSetException::class);
        throw_if(!isset($config['source']), SourceNotSetException::class);
        throw_if(!isset($config['destination']), DestinationNotSetException::class);

        $this->setTitle($config['title']);
        $this->setIdentifier($config['identifier']);
        $this->setVersion(ScormVersions::normalizeScormVersion($config['version']));
        $this->setSource($config['source']);
        $this->setDestination($config['destination']);
        $this->setMasteryScore($config['masteryScore'] ?? 80);
        $this->setStartingPage($config['startingPage'] ?? 'index.html');
        $this->setOrganization($config['organization'] ?? '');
        $this->setPackageFilename($config['packageFilename'] ?? $config['identifier']);
        $this->setCreateZipArchive($config['createZipArchive'] ?? true);
        $this->setMetadataDescription($config['metadataDescription'] ?? null);
    }

    /**
     * Applying metadataConfig
     *
     * @param array $metadataConfig
     */
    private function setMetadataConfig(array $metadataConfig)
    {
        $this->setEntryIdentifier($metadataConfig['entryIdentifier'] ?? '1');
        $this->setCatalogValue($metadataConfig['catalogValue'] ?? 'Catalog');
        $this->setLifeCycleVersion($metadataConfig['lifeCycleVersion'] ?? '1');
        $this->setClassification($metadataConfig['classification'] ?? 'educational objective');
    }

    /**
     * Set course title for SCORM package
     *
     * @param mixed $title
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    /**
     * Get course title for SCORM package
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Set course identifier for SCORM package
     *
     * @param mixed $identifier
     */
    public function setIdentifier(string $identifier)
    {
        $this->identifier = $identifier;
    }

    /**
     * Get course identifier for SCORM package
     *
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * Set version of SCORM package
     *
     * @param mixed $version
     */
    public function setVersion(string $version)
    {
        $this->version = $version;
    }

    /**
     * Get version of SCORM package
     *
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * Set source directory for SCORM package
     *
     * @param mixed $source
     */
    public function setSource(string $source)
    {
        $this->source = $source;
    }

    /**
     * Get destination directory for SCORM package
     *
     * @return string
     */
    public function getSource(): string
    {
        return $this->source;
    }

    /**
     * Set destination directory for SCORM package
     *
     * @param mixed $destination
     */
    public function setDestination(string $destination)
    {
        $this->destination = $destination;
    }

    /**
     * Get destination directory for SCORM package
     *
     * @return string
     */
    public function getDestination(): string
    {
        return $this->destination;
    }

    /**
     * Set passing score for course in SCORM package
     *
     * @param mixed $masteryScore
     */
    public function setMasteryScore(int $masteryScore)
    {
        $this->masteryScore = $masteryScore;
    }

    /**
     * Get passing score for course in SCORM package
     *
     * @return int
     */
    public function getMasteryScore(): int
    {
        return $this->masteryScore;
    }

    /**
     * Set starting page for SCORM package
     *
     * @param mixed $startingPage
     */
    public function setStartingPage(string $startingPage)
    {
        $this->startingPage = $startingPage;
    }

    /**
     * Get starting page for SCORM package
     *
     * @return string
     */
    public function getStartingPage(): string
    {
        return $this->startingPage;
    }

    /**
     * Set organization name for SCORM package
     *
     * @param mixed $organization
     */
    public function setOrganization(string $organization)
    {
        $this->organization = $organization;
    }

    /**
     * Get organization name for SCORM package
     *
     * @return string
     */
    public function getOrganization(): string
    {
        return $this->organization;
    }

    /**
     * Get package filename
     *
     * @param string $packageFilename
     */
    public function setPackageFilename(string $packageFilename)
    {
        $this->packageFilename = $packageFilename;
    }

    /**
     * Set package filename
     *
     * @return string
     */
    public function getPackageFilename(): string
    {
        return $this->packageFilename;
    }

    /**
     * Set flag for create zip archive after building package
     *
     * @param bool $createZipArchive
     */
    public function setCreateZipArchive(bool $createZipArchive): void
    {
        $this->createZipArchive = $createZipArchive;
    }

    /**
     * Get flag for create zip archive after building package
     *
     * @return bool
     */
    public function createZipArchive(): bool
    {
        return $this->createZipArchive;
    }

    /**
     * Set meta description for SCORM package
     *
     * @param string|null $metadataDescription
     */
    public function setMetadataDescription(?string $metadataDescription): void
    {
        if (is_null($metadataDescription)) {
            $this->metadataDescription = "Build Date: " . date("m.d.Y") . "; Technology: html;";
        } else {
            $this->metadataDescription = $metadataDescription;
        }
    }

    /**
     * Get meta description for SCORM package
     *
     * @return string
     */
    public function getMetadataDescription(): string
    {
        return $this->metadataDescription;
    }

    /**
     * Set metadata EntryIdentifier
     *
     * @param string $entryIdentifier
     */
    public function setEntryIdentifier(string $entryIdentifier)
    {
        $this->entryIdentifier = $entryIdentifier;
    }

    /**
     * Get metadata EntryIdentifier
     *
     * @return string
     */
    public function getEntryIdentifier(): string
    {
        return $this->entryIdentifier;
    }

    /**
     * Set metadata catalog value
     *
     * @param string $catalogValue
     */
    public function setCatalogValue(string $catalogValue)
    {
        $this->catalogValue = $catalogValue;
    }

    /**
     * Get metadata catalog value
     *
     * @return string
     */
    public function getCatalogValue(): string
    {
        return $this->catalogValue;
    }

    /**
     * Set metadata lifeCycle version
     *
     * @param string $lifeCycleVersion
     */
    public function setLifeCycleVersion(string $lifeCycleVersion)
    {
        $this->lifeCycleVersion = $lifeCycleVersion;
    }

    /**
     * Get metadata lifeCycle version
     *
     * @return string
     */
    public function getLifeCycleVersion(): string
    {
        return $this->lifeCycleVersion;
    }

    /**
     * Set metadata classification
     *
     * @param string $classification
     */
    public function setClassification(string $classification)
    {
        $this->classification = $classification;
    }

    /**
     * Get metadata classification
     *
     * @return string
     */
    public function getClassification(): string
    {
        return $this->classification;
    }

    /**
     * Build SCORM package
     *
     * @return string
     *
     * @throws Throwable
     */
    public function buildPackage(): string
    {
        $this->createDestinationDirectory();
        $this->createManifestFile();
        $this->copyDefinitionFiles();
        $this->createMetadataFile();

        if ($this->createZipArchive()) {
            $destinationPath = ZipArchiveHelper::createFromDirectory($this->getSource(), $this->getDestination(), $this->getPackageFileName());
            $this->deleteManifestAndDefinitionFiles();
        } else {
            $destinationPath = $this->getSource();
        }

        return $destinationPath;
    }

    /**
     * Create directory for package files
     */
    private function createDestinationDirectory()
    {
        ensure_directory($this->getDestination());
    }

    /**
     * Copy SCORM manifest definition files into destination directory
     */
    private function copyDefinitionFiles()
    {
        $pathToDefinitionFiles = realpath(__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "dist" . DIRECTORY_SEPARATOR . "definitionFiles" . DIRECTORY_SEPARATOR . $this->getVersion());
        $definitionFiles = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($pathToDefinitionFiles, RecursiveDirectoryIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST);

        copy_files($definitionFiles, $pathToDefinitionFiles, $this->getSource() . DIRECTORY_SEPARATOR . self::DIRECTORY_FOR_DEFINITION_FILES);
    }

    /**
     * Create SCORM manifest file in destination directory
     *
     * @throws VersionIsNotSupportedException
     *
     * @throws Throwable
     */
    private function createManifestFile()
    {
        $schema = $this->getScormManifestSchema();

        $xmlString = XMLFromArrayCreator::createManifestXMLFromSchema($schema);

        file_put_contents($this->getSource() . DIRECTORY_SEPARATOR . self::XML_MANIFEST_FILE_NAME, $xmlString);
    }

    /**
     * Returns SCORM manifest's schema
     *
     * @return array
     *
     * @throws VersionIsNotSupportedException
     */
    private function getScormManifestSchema(): array
    {
        /** @var AbstractScormSchema $scormSchemaClass */
        switch ($this->getVersion()) {
            case ScormVersions::SCORM__1_2__VERSION:
                $scormVersionForSchema = '1.2';
                $scormSchemaClass = Scorm12Schema::class;
                break;
            case ScormVersions::SCORM__2004_3__VERSION:
                $scormVersionForSchema = '2004 3rd Edition';
                $scormSchemaClass = Scorm2004Edition3Schema::class;
                break;
            case ScormVersions::SCORM__2004_4__VERSION:
                $scormVersionForSchema = '2004 4th Edition';
                $scormSchemaClass = Scorm2004Edition4Schema::class;
                break;
            default:
                throw new VersionIsNotSupportedException();
        }

        return $scormSchemaClass::getSchema(
            $this->getTitle(),
            $this->getIdentifier(),
            $this->getOrganization(),
            $scormVersionForSchema,
            $this->getMasteryScore(),
            $this->getStartingPage(),
            $this->getSource(),
            $this->getMetadataDescription()
        );
    }

    /**
     * Deletes SCORM manifest file and definition files after creating zip archive with package
     */
    private function deleteManifestAndDefinitionFiles()
    {
        unlink($this->getSource() . DIRECTORY_SEPARATOR . self::XML_MANIFEST_FILE_NAME);
        delete_directory_if_exists($this->getSource() . DIRECTORY_SEPARATOR . self::DIRECTORY_FOR_DEFINITION_FILES);
    }

    /**
     * Returns Metadata schema.
     *
     * @return array
     *
     * @throws VersionIsNotSupportedException
     */
    private function getMetadataSchema(): array
    {
        /** @var AbstractMetadataSchema $metadataSchemaClass */
        switch ($this->getVersion()) {
            case ScormVersions::SCORM__1_2__VERSION:
            case ScormVersions::SCORM__2004_3__VERSION:
                return [];
            case ScormVersions::SCORM__2004_4__VERSION:
                $metadataSchemaClass = Metadata2004Edition4Schema::class;
                break;
            default:
                throw new VersionIsNotSupportedException();
        }

        return $metadataSchemaClass::getSchema(
            $this->getTitle(),
            $this->getEntryIdentifier(),
            $this->getCatalogValue(),
            $this->getLifeCycleVersion(),
            $this->getClassification()
        );
    }

    /**
     * Create Metadata file in destination directory
     *
     * @throws Throwable
     */
    private function createMetadataFile()
    {
        $schema = $this->getMetadataSchema();

        if (!empty($schema)) {
            $xmlString = XMLFromArrayCreator::createManifestXMLFromSchema($schema);

            file_put_contents($this->getSource() . DIRECTORY_SEPARATOR . Packager::XML_METADATA_FILE_NAME, $xmlString);
        }
    }
}