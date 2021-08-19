<?php

declare(strict_types=1);

namespace FRUIT\StaticExport\Event;

final class CreateExportEvent
{

    protected \ZipArchive $zip;

    /**
     * @param \ZipArchive $zip
     */
    public function __construct(\ZipArchive $zip)
    {
        $this->zip = $zip;
    }

    public function getZip(): \ZipArchive
    {
        return $this->zip;
    }

}
