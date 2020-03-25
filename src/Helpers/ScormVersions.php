<?php

declare(strict_types=1);

namespace Romanpravda\Scormpackager\Helpers;

use Romanpravda\Scormpackager\Exceptions\VersionIsNotSupportedException;

class ScormVersions
{
    const SCORM__2004_3__VERSION = '2004.3';
    const SCORM__2004_4__VERSION = '2004.4';
    const SCORM__1_2__VERSION = '1.2';

    /**
     * Normalize SCORM version
     *
     * @param string $version
     *
     * @return string
     *
     * @throws VersionIsNotSupportedException
     */
    public static function normalizeScormVersion(string $version): string
    {
        switch ($version) {
            case '1.2':
                return self::SCORM__1_2__VERSION;
                break;
            case '2004.3':
            case '2004 3th Edition':
            case 'scorm20043rdedition':
                return self::SCORM__2004_3__VERSION;
                break;
            case '2004.4':
            case '2004 4th Edition':
            case 'scorm20044thedition':
                return self::SCORM__2004_4__VERSION;
                break;
            default:
                throw new VersionIsNotSupportedException();
        }
    }
}