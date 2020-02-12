<?php

namespace srag\Plugins\OnlyOffice\StorageService\DTO;

use ilDateTime;

/**
 * Class FileVersion
 *
 * @package srag\Plugins\OnlyOffice\StorageService\DTO
 *
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class FileVersion
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
     * FileVersion constructor.
     *
     * @param int        $version
     * @param ilDateTime $created_at
     * @param int        $user_id
     * @param string     $url
     */
    public function __construct(int $version, ilDateTime $created_at, int $user_id, string $url)
    {
        $this->version = $version;
        $this->created_at = $created_at;
        $this->user_id = $user_id;
        $this->url = $url;
    }


    /**
     * @return int
     */
    public function getVersion() : int
    {
        return $this->version;
    }


    /**
     * @return ilDateTime
     */
    public function getCreatedAt() : ilDateTime
    {
        return $this->created_at;
    }


    /**
     * @return int
     */
    public function getUserId() : int
    {
        return $this->user_id;
    }


    /**
     * @return string
     */
    public function getUrl() : string
    {
        return $this->url;
    }
}