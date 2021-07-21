<?php

namespace srag\Plugins\OnlyOffice\StorageService\Infrastructure\File;

use ActiveRecord;
use ilDateTime;
use ilDateTimeException;
use srag\Plugins\OnlyOffice\StorageService\Infrastructure\Common\UUID;

/**
 * Class File
 * @package srag\Plugins\OnlyOffice\StorageService\Infrastructure
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class FileVersionAR extends ActiveRecord
{

    const TABLE_NAME = 'xono_file_version';

    /**
     * @return string
     */
    public function getConnectorContainerName()
    {
        return self::TABLE_NAME;
    }

    /**
     * @var int
     * @con_has_field    true
     * @con_fieldtype    integer
     * @con_length       8
     * @con_is_notnull   true
     * @con_is_primary   true
     * @con_sequence     true
     */
    protected $id;
    /**
     * @var UUID
     * @con_has_field    true
     * @con_fieldtype    text
     * @con_length       256
     * @con_is_notnull   true
     */
    protected $file_uuid;
    /**
     * @var int
     * @con_has_field    true
     * @con_fieldtype    integer
     * @con_length       8
     * @con_is_notnull   true
     */
    protected $version;
    /**
     * @var int
     * @con_has_field    true
     * @con_fieldtype    integer
     * @con_length       8
     * @con_is_notnull   true
     */
    protected $user_id;
    /**
     * @var ilDateTime
     * @db_has_field         true
     * @db_fieldtype         timestamp
     * @con_is_notnull       true
     */
    protected $created_at;
    /**
     * @var string
     * @db_has_field         true
     * @db_fieldtype         text
     * @con_is_notnull       true
     */
    protected $url;

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
     * @return int
     */
    public function getId() : int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return UUID
     */
    public function getFileUuid() : UUID
    {
        return $this->file_uuid;
    }

    /**
     * @param UUID $file_uuid
     */
    public function setFileUuid(UUID $file_uuid)
    {
        $this->file_uuid = $file_uuid;
    }

    /**
     * @return ilDateTime
     */
    public function getCreatedAt() : ilDateTime
    {
        return $this->created_at;
    }

    /**
     * @param ilDateTime $created_at
     */
    public function setCreatedAt(ilDateTime $created_at)
    {
        $this->created_at = $created_at;
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
        $this->url = $url;
    }

    /**
     * @param $field_name
     * @return mixed
     */
    public function sleep($field_name)
    {
        switch ($field_name) {
            case 'file_uuid':
                return $this->file_uuid->asString();
            case 'created_at':
                return $this->created_at->get(IL_CAL_FKT_DATE, 'Y-m-d H:i:s');
            default:
                return parent::sleep($field_name);
        }
    }

    /**
     * @param $field_name
     * @param $field_value
     * @return mixed
     * @throws ilDateTimeException
     */
    public function wakeUp($field_name, $field_value)
    {
        switch ($field_name) {
            case 'file_uuid':
                return new UUID($field_value);
            case 'created_at':
                return new ilDateTime($field_value, IL_CAL_DATE);
            default:
                return parent::wakeUp($field_name, $field_value);
        }
    }
}