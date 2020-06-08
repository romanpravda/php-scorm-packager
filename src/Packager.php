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
use Romanpravda\Scormpackager\Schemas\AbstractScormSchema;
use Romanpravda\Scormpackager\Schemas\Scorm12Schema;
use Romanpravda\Scormpackager\Schemas\Scorm2004Schema;
use Throwable;

class Packager
{
    const XML_MANIFEST_FILE_NAME = "imsmanifest.xml";
    const DIRECTORY_FOR_DEFINITION_FILES = "definitionFiles";

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
     * Packager constructor.
     *
     * @param array $config
     *
     * @throws Throwable
     */
    public function __construct(array $config)
    {
        $this->setConfig($config);
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
        $pathToDefinitionFiles = realpath(__DIR__.DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."dist".DIRECTORY_SEPARATOR."definitionFiles".DIRECTORY_SEPARATOR.$this->getVersion());
        $definitionFiles = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($pathToDefinitionFiles, RecursiveDirectoryIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST);

        copy_files($definitionFiles, $pathToDefinitionFiles, $this->getSource().DIRECTORY_SEPARATOR.self::DIRECTORY_FOR_DEFINITION_FILES);
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

        file_put_contents($this->getSource().DIRECTORY_SEPARATOR.self::XML_MANIFEST_FILE_NAME, $xmlString);
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
                $scormSchemaClass = Scorm2004Schema::class;
                break;
            case ScormVersions::SCORM__2004_4__VERSION:
                $scormVersionForSchema = '2004 4th Edition';
                $scormSchemaClass = Scorm2004Schema::class;
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
            $this->getSource()
        );
    }

    /**
     * Deletes SCORM manifest file and definition files after creating zip archive with package
     */
    private function deleteManifestAndDefinitionFiles()
    {
        unlink($this->getSource().DIRECTORY_SEPARATOR.self::XML_MANIFEST_FILE_NAME);
        delete_directory_if_exists($this->getSource().DIRECTORY_SEPARATOR.self::DIRECTORY_FOR_DEFINITION_FILES);
    }
}