<?php

namespace srag\Plugins\OnlyOffice\StorageService\Infrastructure\File;

use srag\Plugins\OnlyOffice\StorageService\Infrastructure\Common\UUID;

/**
 * interface FileChangeRepository
 * @package srag\Plugins\OnlyOffice\StorageService\Infrastructure\File
 * @author  Sophie Pfiser <sophie@fluxlabs.ch>
 */
interface FileChangeRepository
{

    /**
     * @param int    $change_id
     * @param UUID   $file_uuid
     * @param int    $version
     * @param string $changesObjectString
     * @param string $serverVersion
     * @param string $changesUrl
     * @return mixed
     */
    public function create(
        int $change_id,
        UUID $file_uuid,
        int $version,
        string $changesObjectString,
        string $serverVersion,
        string $changesUrl
    );

}