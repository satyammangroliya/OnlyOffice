<?php

namespace srag\Plugins\OnlyOffice\StorageService\Infrastructure\Common;

use Exception;
use Ramsey\Uuid\Uuid as RamseyUuid;

/**
 * Class UUID
 *
 * @package srag\Plugins\OnlyOffice\StorageService\Infrastructure\Common
 *
 * @author  Theodor Truffer <thoe@fluxlabs.ch>
 */
class UUID
{

    /**
     * @var string
     */
    protected $uuid;


    /**
     * UUID constructor.
     *
     * @param string $uuid
     */
    public function __construct(string $uuid = '')
    {
        $this->uuid = $uuid !== '' ? $uuid : RamseyUuid::uuid4()->toString();
    }


    /**
     * @return string
     */
    public function asString() : string
    {
        return $this->uuid;
    }
}