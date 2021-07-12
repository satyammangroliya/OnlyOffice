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
 * @package srag\Plugins\OnlyOffice\StorageService\Infrastructure
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class ilDBFileVersionRepository implements FileVersionRepository
{

    /**
     * @param UUID       $file_uuid
     * @param int        $user_id
     * @param ilDateTime $created_at
     * @return int
     * @throws arException
     */
    public function create(UUID $file_uuid, int $user_id, ilDateTime $created_at, string $url) : int
    {
        $file_version_AR = new FileVersionAR();
        $file_version_AR->setFileUuid($file_uuid);
        $file_version_AR->setVersion($this->determineVersion($file_uuid));
        $file_version_AR->setUserId($user_id);
        $file_version_AR->setCreatedAt($created_at);
        $file_version_AR->setUrl($url);
        $file_version_AR->create();
        return $file_version_AR->getVersion();
    }

    /**
     * @param UUID $file_uuid
     * @return int
     * @throws arException
     */
    protected function determineVersion(UUID $file_uuid) : int
    {
        /** @var FileVersionAR $latest_version */
        $latest_version = FileVersionAR::where(['file_uuid' => $file_uuid->asString()])->orderBy('version',
            'desc')->first();
        return $latest_version ? $latest_version->getVersion() : FileVersion::FIRST_VERSION;
    }

    /**
     * @param int $object_id
     * @return FileVersion
     */
    public function getByObjectID(int $object_id) : FileVersion
    {
        /** @var FileVersionAR $file_version_ar */
        $file_version_ar = FileVersionAR::where(['id' => $object_id])->first();
        return $this->buildFileVersionFromAR($file_version_ar);
    }

    /**
     * Returns all versions of a file
     * @param UUID $file_uuid
     * @return array
     * @throws arException
     */
    public function getAllVersions(UUID $file_uuid) : array
    {
        /** @var array $all_file_version_ar */
        $all_file_version_ar = FileVersionAR::where(['file_uuid' => $file_uuid->asString()])->orderBy('version',
            'desc')->get();
        $length = count($all_file_version_ar);
        $result = array();
        for ($i = 1; $i <= $length; $i++) { //TODO: Warum beginnt der Index hier bei 1?
            /** @var FileVersionAR $next_ar */
            $next_ar = $all_file_version_ar[$i];
            $fileVersion = $this->buildFileVersionFromAR($next_ar);
            array_push($result, $fileVersion);
        }
        return $result;
    }

    /**
     * @param FileVersionAR $ar
     * @return FileVersion
     */
    protected function buildFileVersionFromAR(FileVersionAR $ar) : FileVersion
    {
        $version = $ar->getVersion();
        $created_at = $ar->getCreatedAt();
        $user_id = $ar->getUserId();
        $url = $ar->getUrl();
        $file_uuid = $ar->getFileUuid();
        return new FileVersion($version, $created_at, $user_id, $url, $file_uuid);
    }
}