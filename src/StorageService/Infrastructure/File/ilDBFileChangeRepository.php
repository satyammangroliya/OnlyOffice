<?php

namespace srag\Plugins\OnlyOffice\StorageService\Infrastructure\File;

use srag\Plugins\OnlyOffice\StorageService\Infrastructure\Common\UUID;

/**
 * Class ilDBFIleChangeRepository
 *
 * @author Sophie Pfister <sophie@fluxlabs.ch>
 * @package srag\Plugins\OnlyOffice\StorageService\Infrastructure\File
 */
class ilDBFileChangeRepository implements FileChangeRepository
{

    public function create(
        int $change_id,
        UUID $file_uuid,
        int $version,
        string $changesObjectString,
        string $serverVersion,
        string $changesUrl
    ) {
        $file_change_AR = new FileChangeAR();
        $file_change_AR->setChangeId($change_id);
        $file_change_AR->setFileUuid($file_uuid);
        $file_change_AR->setVersion($version);
        $file_change_AR->setChanges($changesObjectString);
        $file_change_AR->setServerVersion($serverVersion);
        $file_change_AR->setChangesUrl($changesUrl);
        $file_change_AR->create();
    }

    // ToDo: Do this in a better way
    public function getNextId() : int
    {
        if ($latest = FileChangeAR::where('TRUE')->orderBy('change_id', 'desc')->first())
            return $latest->getChangeId() + 1;
        else
            return 1;
    }
}