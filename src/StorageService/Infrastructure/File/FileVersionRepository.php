<?php

namespace srag\Plugins\OnlyOffice\StorageService\Infrastructure\File;

use ilDateTime;
use srag\Plugins\OnlyOffice\StorageService\Infrastructure\Common\UUID;

/**
 * Class FileRepository
 *
 * @package srag\Plugins\OnlyOffice\StorageService\Infrastructure
 *
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
interface FileVersionRepository
{

    /**
     * @param UUID       $file_uuid
     * @param int        $user_id
     *
     * @param ilDateTime $created_at
     *
     * @return int created version
     */
    public function create(UUID $file_uuid, int $user_id, ilDateTime $created_at) : int;
}