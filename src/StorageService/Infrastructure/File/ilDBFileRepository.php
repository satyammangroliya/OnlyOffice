<?php

namespace srag\Plugins\OnlyOffice\StorageService\Infrastructure\File;

use srag\Plugins\OnlyOffice\StorageService\Infrastructure\Common\UUID;
use srag\Plugins\OnlyOffice\StorageService\DTO\File;

/**
 * Class ilDBFileRepository
 * @package srag\Plugins\OnlyOffice\StorageService\Infrastructure\File
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class ilDBFileRepository implements FileRepository
{

    /**
     * @inheritDoc
     */
    public function create(UUID $file_uuid, int $obj_id, string $title, string $file_type, string $open_setting)
    {
        $file_AR = new FileAR();
        $file_AR->setUUID($file_uuid);
        $file_AR->setObjId($obj_id);
        $file_AR->setTitle($title);
        $file_AR->setFileType($file_type);
        $file_AR->setOpenSetting($open_setting);
        $file_AR->create();
    }

    public function getFile(int $obj_id) : File
    {
        $file_ar = FileAR::where(['obj_id' => $obj_id])->first();
        return $this->buildFileFromAR($file_ar);
    }

    protected function buildFileFromAR(FileAR $ar) : File
    {
        $uuid = $ar->getUUID();
        $obj_id = $ar->getObjId();
        $title = $ar->getTitle();
        $file_type = $ar->getFileType();
        $open_setting = $ar->getOpenSetting();
        return new File($uuid, $obj_id, $title, $file_type, $open_setting);

    }
}