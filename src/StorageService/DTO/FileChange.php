<?php

namespace srag\Plugins\OnlyOffice\StorageService\DTO;

use srag\Plugins\OnlyOffice\StorageService\Infrastructure\Common\UUID;

/**
 * Class FileChange
 * @author  Sophie Pfister <sophie@fluxlabs.ch>
 */
class FileChange
{

    /**
     * @var int
     */
    protected $change_id;

    /**
     * @var UUID
     */
    protected $file_uuid;
    /**
     * @var int
     */
    protected $version;

    /**
     * @var string
     */
    protected $changesObjectString;
    /**
     * @var string
     */
    protected $serverVersion;
    /**
     * @var string
     */
    protected $changesUrl;

    public function __construct(
        int $change_id,
        UUID $file_uuid,
        int $version,
        string $changesObjectString,
        string $serverVersion,
        string $changesUrl
    ) {
        $this->change_id = $change_id;
        $this->file_uuid = $file_uuid;
        $this->version = $version;
        $this->changesObjectString = $changesObjectString;
        $this->serverVersion = $serverVersion;
        $this->changesUrl = $changesUrl;
    }

    public function setChangeId(int $change_id)
    {
        $this->change_id = $change_id;
    }

    public function getChangeId() : int
    {
        return $this->change_id;
    }

    public function setFileUuid(UUID $file_uuid)
    {
        $this->file_uuid = $file_uuid;
    }

    public function getFileUuid() : UUID
    {
        return $this->file_uuid;
    }

    public function setVersion(int $version)
    {
        $this->version = $version;
    }

    public function getVersion() : int
    {
        return $this->version;
    }

    public function setChangesObjectString(string $changes)
    {
        $this->changesObjectString = $changes;
    }

    public function getChangesObjectString() : string
    {
        return $this->changesObjectString;
    }

    public function setServerVersion(string $serverVersion)
    {
        $this->serverVersion = $serverVersion;
    }

    public function getServerVersion() : string
    {
        return $this->serverVersion;
    }

    public function setChangesUrl(string $changesUrl)
    {
        $this->changesUrl = $changesUrl;
    }

    public function getChangesUrl() : string
    {
        return $this->changesUrl;
    }

}