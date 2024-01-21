<?php

namespace FRUIT\StaticExport\Service;

use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class PathService
{
    public const BASE_FOLDER = '/export';

    public const COLLECT_FOLDER = '/collect';

    public const ARCHIVE_FOLDER = '/archive';

    public function getStaticExportBasePath(): string
    {
        return $this->checkExistenceAndReturn(Environment::getVarPath() . self::BASE_FOLDER);
    }

    public function getArchiveFolder(): string
    {
        return $this->checkExistenceAndReturn($this->getStaticExportBasePath() . self::ARCHIVE_FOLDER);
    }

    public function getCollectFolder(): string
    {
        return $this->checkExistenceAndReturn($this->getStaticExportBasePath() . self::COLLECT_FOLDER);
    }

    protected function checkExistenceAndReturn(string $path): string
    {
        if (!is_dir($path)) {
            GeneralUtility::mkdir_deep($path);
        }
        return $path;
    }
}