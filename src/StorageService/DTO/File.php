<?php

namespace srag\Plugins\OnlyOffice\StorageService\DTO;

use srag\Plugins\OnlyOffice\StorageService\Infrastructure\Common\UUID;

/**
 * Class File
 *
 * @package srag\Plugins\OnlyOffice\StorageService\DTO
 *
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class File
{

    /**
     * @var UUID
     */
    protected $uuid;
    /**
     * @var int
     */
    protected $obj_id;
    /**
     * @var string
     */
    protected $title;
    /**
     * @var string
     */
    protected $file_type;

    /** @var string  */
    protected $open_setting;


    /**
     * File constructor.
     *
     * @param UUID          $uuid
     * @param int           $obj_id
     * @param string        $title
     * @param string        $file_type
     */
    public function __construct(UUID $uuid, int $obj_id, string $title, string $file_type, string $open_setting)
    {
        $this->uuid = $uuid;
        $this->title = $title;
        $this->file_type = $file_type;
        $this->obj_id = $obj_id;
        $this->open_setting = $open_setting;
    }


    /**
     * @return UUID
     */
    public function getUuid() : UUID
    {
        return $this->uuid;
    }

    /**
     * @return string
     */
    public function getTitle() : string
    {
        return $this->title;
    }


    /**
     * @return int
     */
    public function getObjId() : int
    {
        return $this->obj_id;
    }


    /**
     * @return string
     */
    public function getFileType() : string
    {
        return $this->file_type;
    }

    public function getFileUuid(): UUID
    {
        return $this->uuid;
    }

    public function getOpenSetting() : string
    {
        return $this->open_setting;
    }
    public function setOpenSetting(string $open_setting) {
        if ($open_setting == "editor" || $open_setting == "browser" || $open_setting == "ilias")
            $this->open_setting = $open_setting;
    }

}