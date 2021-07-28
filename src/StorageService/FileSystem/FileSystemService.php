<?php

namespace srag\Plugins\OnlyOffice\StorageService\FileSystem;

use ILIAS\DI\Container;
use ILIAS\Filesystem\Exception\IOException;
use ILIAS\FileUpload\DTO\UploadResult;
use ILIAS\FileUpload\Location;
use srag\Plugins\OnlyOffice\StorageService\DTO\FileVersion;

/**
 * Class FileSystemService
 * @package srag\Plugins\OnlyOffice\StorageService\FileSystem
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class FileSystemService
{

    const BASE_PATH = '/only_office/';
    /**
     * @var Container
     */
    protected $dic;

    /**
     * FileRepository constructor.
     * @param Container $dic
     */
    public function __construct(Container $dic)
    {
        $this->dic = $dic;
    }

    /**
     * @param UploadResult $upload_result
     * @param int          $obj_id
     * @param string       $file_id
     * @throws IOException
     */
    public function storeUpload(UploadResult $upload_result, int $obj_id, string $file_id, string $file_name = FileVersion::FIRST_VERSION) : string
    {
        $path = $this->createAndGetPath($obj_id, $file_id);
        $this->dic->upload()->moveOneFileTo(
            $upload_result,
            $path,
            Location::WEB,
            $file_name
        );
        return $path;
    }

    /**
     * @param int    $obj_id
     * @param string $file_id
     * @return string
     * @throws IOException
     */
    protected function createAndGetPath(int $obj_id, string $file_id) : string
    {
        $path = self::BASE_PATH . $obj_id . DIRECTORY_SEPARATOR . $file_id . DIRECTORY_SEPARATOR;
        if (!$this->dic->filesystem()->storage()->hasDir($path)) {
            $this->dic->filesystem()->storage()->createDir($path);
        }

        return $path;
    }
}