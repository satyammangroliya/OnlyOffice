<?php

namespace srag\Plugins\OnlyOffice\StorageService\Infrastructure\File;

use srag\Plugins\OnlyOffice\StorageService\Infrastructure\Common\UUID;

/**
 * Class ilDBFileRepository
 *
 * @package srag\Plugins\OnlyOffice\StorageService\Infrastructure\File
 *
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class ilDBFileRepository implements FileRepository
{

    /**
     * @inheritDoc
     */
    public function create(UUID $file_uuid, int $obj_id, string $title, string $file_type)
    {
        $file_AR = new FileAR();
        $file_AR->setUUID($file_uuid);
        $file_AR->setObjId($obj_id);
        $file_AR->setTitle($title);
        $file_AR->setFileType($file_type);
        $file_AR->create();
    }
}