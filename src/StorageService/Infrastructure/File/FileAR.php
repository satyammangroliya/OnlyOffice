<?php

namespace srag\Plugins\OnlyOffice\StorageService\Infrastructure\File;

use ActiveRecord;
use srag\Plugins\OnlyOffice\StorageService\Infrastructure\Common\UUID;

/**
 * Class FileAR
 * @package srag\Plugins\OnlyOffice\StorageService\Infrastructure\File
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class FileAR extends ActiveRecord
{

    const TABLE_NAME = 'xono_file';

    /**
     * @return string
     */
    public function getConnectorContainerName()
    {
        return self::TABLE_NAME;
    }

    /**
     * @var UUID
     * @con_has_field    true
     * @con_fieldtype    text
     * @con_length       256
     * @con_is_unique    true
     * @con_is_notnull   true
     */
    protected $uuid;

    /**
     * @var int
     * @con_has_field    true
     * @con_fieldtype    integer
     * @con_length       8
     * @con_is_primary   true
     */
    protected $obj_id;

    /**
     * @var String
     * @db_has_field        true
     * @db_fieldtype        text
     * @con_is_notnull      true
     * @db_length           256
     */
    protected $title;

    /**
     * @var String
     * @db_has_field        true
     * @db_fieldtype        text
     * @con_is_notnull      true
     * @db_length           256
     */
    protected $file_type;

    /**
     * @var string
     * @db_has_field true
     * @db_fieldtype text
     * @db_length 10
     */
    protected $open_setting;

    /**
     * @return UUID
     */
    public function getUUID() : UUID
    {
        return $this->uuid;
    }

    /**
     * @param UUID $uuid
     */
    public function setUUID(UUID $uuid)
    {
        $this->uuid = $uuid;
    }

    /**
     * @return UUID
     */
    public function getId() : UUID
    {
        return $this->id;
    }

    /**
     * @param UUID $id
     */
    public function setId(UUID $id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getObjId() : int
    {
        return $this->obj_id;
    }

    /**
     * @param int $obj_id
     */
    public function setObjId(int $obj_id)
    {
        $this->obj_id = $obj_id;
    }

    /**
     * @return String
     */
    public function getTitle() : string
    {
        return $this->title;
    }

    /**
     * @param String $title
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    /**
     * @return String
     */
    public function getFileType() : string
    {
        return $this->file_type;
    }

    /**
     * @param String $file_type
     */
    public function setFileType(string $file_type)
    {
        $this->file_type = $file_type;
    }

    public function getOpenSetting() : string {
        return $this->open_setting;
    }

    public function setOpenSetting(string $open_setting)  {
        if ($open_setting == "editor" || $open_setting == "browser" || $open_setting == "ilias")
            $this->open_setting = $open_setting;
    }

    /**
     * @param $field_name
     * @return mixed
     */
    public function sleep($field_name)
    {
        switch ($field_name) {
            case 'uuid':
                return $this->uuid->asString();
            default:
                return parent::sleep($field_name);
        }
    }

    /**
     * @param $field_name
     * @param $field_value
     * @return mixed
     */
    public function wakeUp($field_name, $field_value)
    {
        switch ($field_name) {
            case 'uuid':
                return new UUID($field_value);
            default:
                return parent::wakeUp($field_name, $field_value);
        }
    }
}