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
    /**
     * @var FileVersion[]
     */
    protected $file_versions;


    /**
     * File constructor.
     *
     * @param UUID          $uuid
     * @param int           $obj_id
     * @param string        $title
     * @param string        $file_type
     * @param FileVersion[] $file_versions
     */
    public function __construct(UUID $uuid, int $obj_id, string $title, string $file_type, array $file_versions = [])
    {
        $this->uuid = $uuid;
        $this->title = $title;
        $this->file_type = $file_type;
        $this->file_versions = $file_versions;
        $this->obj_id = $obj_id;
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

    /**
     * @return FileVersion[]
     */
    public function getFileVersions() : array
    {
        return $this->file_versions;
    }


    /**
     * @param FileVersion[] $file_versions
     */
    public function setFileVersions(array $file_versions)
    {
        $this->file_versions = $file_versions;
    }
}