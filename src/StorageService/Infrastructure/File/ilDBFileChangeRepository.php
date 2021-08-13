<?php

namespace srag\Plugins\OnlyOffice\StorageService\Infrastructure\File;

use srag\Plugins\OnlyOffice\StorageService\Infrastructure\Common\UUID;
use srag\Plugins\OnlyOffice\StorageService\DTO\FileChange;

/**
 * Class ilDBFIleChangeRepository
 * @author  Sophie Pfister <sophie@fluxlabs.ch>
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
        $file_change_AR->setChangesObjectString($changesObjectString);
        if(! $serverVersion)
            $file_change_AR->setServerVersion(self::DEFAULT_SERVER_VERSION);
        else
            $file_change_AR->setServerVersion($serverVersion);
        $file_change_AR->setChangesUrl($changesUrl);
        $file_change_AR->create();
    }

    // ToDo: Do this in a better way
    public function getNextId() : int
    {
        if ($latest = FileChangeAR::where('TRUE')->orderBy('change_id', 'desc')->first()) {
            return $latest->getChangeId() + 1;
        } else {
            return 1;
        }
    }

    public function getAllChanges(string $uuid) : array
    {
        $result = array();
        $allChanges = FileChangeAR::where(['file_uuid' => $uuid])->orderBy('version', 'asc')->get();
        foreach ($allChanges as $change) {
            $fc = $this->buildFileChangeFromAR($change);
            $result[$fc->getVersion()] = $fc;
        }
        return $result;
    }

    protected function buildFileChangeFromAR(FileChangeAR $fc_ar) : FileChange
    {
        $change_id = $fc_ar->getChangeId();
        $file_uuid = $fc_ar->getFileUuid();
        $version = $fc_ar->getVersion();
        $changesObjectString = $fc_ar->getChangesObjectString();
        $serverVersion = $fc_ar->getServerVersion();
        $changesUrl = $fc_ar->getChangesUrl();
        return new FileChange($change_id, $file_uuid, $version, $changesObjectString, $serverVersion, $changesUrl);
    }

    public function getChange(string $uuid, int $version) : FileChange
    {
        /** @var FileChangeAR $file_change_ar */
        $file_change_ar = FileChangeAR::where(['file_uuid' => $uuid, 'version' => $version])->first();
        return $this->buildFileChangeFromAR($file_change_ar);
    }
}