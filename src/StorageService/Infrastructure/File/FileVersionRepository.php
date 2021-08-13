<?php

namespace srag\Plugins\OnlyOffice\StorageService\Infrastructure\File;

use ilDateTime;
use srag\Plugins\OnlyOffice\StorageService\Infrastructure\Common\UUID;
use srag\Plugins\OnlyOffice\StorageService\DTO\FileVersion;

/**
 * Class FileRepository
 * @package srag\Plugins\OnlyOffice\StorageService\Infrastructure
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
interface FileVersionRepository
{

    /**
     * @param UUID       $file_uuid
     * @param int        $user_id
     * @param string     $url
     * @param ilDateTime $created_at
     * @return int created version
     */
    public function create(UUID $file_uuid, int $user_id, ilDateTime $created_at, string $url) : int;

    public function getByObjectID(int $object_id) : FileVersion;

    public function getAllVersions(UUID $file_uuid) : array;

    public function getLatestVersion(UUID $file_uuid): FileVersion;

    public function getPreviousVersion(string $uuid, int $version): FileVersion;
}