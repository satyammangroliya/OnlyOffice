<?php

namespace srag\Plugins\OnlyOffice\StorageService\DTO;

use ilDateTime;
use srag\Plugins\OnlyOffice\StorageService\Infrastructure\Common\UUID;

/**
 * Class FileVersion
 * @package srag\Plugins\OnlyOffice\StorageService\DTO
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class FileVersion implements \JsonSerializable
{

    const FIRST_VERSION = 1;
    /**
     * @var int
     */
    protected $version;
    /**
     * @var ilDateTime
     */
    protected $created_at;
    /**
     * @var int
     */
    protected $user_id;
    /**
     * @var string
     */
    protected $url;
    /**
     * @var UUID
     */
    protected $file_uuid;

    /**
     * FileVersion constructor.
     * @param int        $version
     * @param ilDateTime $created_at
     * @param int        $user_id
     * @param string     $url
     */
    public function __construct(int $version, ilDateTime $created_at, int $user_id, string $url, UUID $file_uuid)
    {
        $this->version = $version;
        $this->created_at = $created_at;
        $this->user_id = $user_id;
        $this->url = $url;
        $this->file_uuid = $file_uuid;
    }

    /**
     * @return int
     */
    public function getVersion() : int
    {
        return $this->version;
    }

    /**
     * @param int $version
     */
    public function setVersion(int $version)
    {
        $this->version = $version;
    }

    /**
     * @return ilDateTime
     */
    public function getCreatedAt() : ilDateTime
    {
        return $this->created_at;
    }

    /**
     * @param ilDateTime $date
     */
    public function setCreatedAt(ilDateTime $date)
    {
        $this->created_at = $date;
    }

    /**
     * @return int
     */
    public function getUserId() : int
    {
        return $this->user_id;
    }

    /**
     * @param int $user_id
     */
    public function setUserId(int $user_id)
    {
        $this->user_id = $user_id;
    }

    /**
     * @return string
     */
    public function getUrl() : string
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl(string $url)
    {
        $this->url = url;
    }

    /**
     * @return string
     */
    public function getFileUuid() : UUID
    {
        return $this->file_uuid;
    }

    /**
     * @param string $uuid
     */
    public function setFileUuid(UUID $uuid)
    {
        $this->file_uuid = $uuid;
    }

    public function jsonSerialize()
    {
        global $DIC;
        $user = new \ilObjUser($this->user_id);
        return [
            'version' => $this->version,
            'createdAt' => $this->created_at->get(2),
            'userId' => $this->user_id,
            'user' => $user->getPublicName(),
            'url' => $this->url,
            'uuid' => $this->file_uuid->asString()
        ];
    }

}