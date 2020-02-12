<?php

namespace srag\Plugins\OnlyOffice\StorageService\Infrastructure\File;

use arException;
use ilDateTime;
use ilDateTimeException;
use ILIAS\DI\Container;
use srag\Plugins\OnlyOffice\StorageService\DTO\FileVersion;
use srag\Plugins\OnlyOffice\StorageService\Infrastructure\Common\UUID;

/**
 * Class FileRepository
 *
 * @package srag\Plugins\OnlyOffice\StorageService\Infrastructure
 *
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class ilDBFileVersionRepository implements FileVersionRepository
{

    /**
     * @param UUID       $file_uuid
     * @param int        $user_id
     *
     * @param ilDateTime $created_at
     *
     * @return int
     * @throws arException
     */
    public function create(UUID $file_uuid, int $user_id, ilDateTime $created_at) : int
    {
        $file_version_AR = new FileVersionAR();
        $file_version_AR->setFileUuid($file_uuid);
        $file_version_AR->setVersion($this->determineVersion($file_uuid));
        $file_version_AR->setUserId($user_id);
        $file_version_AR->setCreatedAt($created_at);
        $file_version_AR->create();
        return $file_version_AR->getVersion();
    }


    /**
     * @param UUID $file_uuid
     *
     * @return int
     * @throws arException
     */
    protected function determineVersion(UUID $file_uuid) : int
    {
        /** @var FileVersionAR $latest_version */
        $latest_version = FileVersionAR::where(['file_uuid' => $file_uuid->asString()])->orderBy('version', 'desc')->first();
        return $latest_version ? $latest_version->getVersion() : FileVersion::FIRST_VERSION;
    }
}