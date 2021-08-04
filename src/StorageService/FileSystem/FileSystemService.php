<?php

namespace srag\Plugins\OnlyOffice\StorageService\FileSystem;

use ILIAS\DI\Container;
use ILIAS\Filesystem\Exception\IOException;
use ILIAS\FileUpload\DTO\UploadResult;
use ILIAS\FileUpload\Location;
use srag\Plugins\OnlyOffice\StorageService\DTO\FileVersion;
use ILIAS\Filesystem\Stream\Streams;

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
    public function storeUploadResult(UploadResult $upload_result, int $obj_id, string $file_id, string $file_name = FileVersion::FIRST_VERSION) : string
    {
        $ext = pathinfo($upload_result->getName(), PATHINFO_EXTENSION);
        $file_name .= '.' . $ext;

        $path = $this->createAndGetPath($obj_id, $file_id);
        $this->dic->upload()->moveOneFileTo(
            $upload_result,
            $path,
            Location::WEB,
            $file_name
        );
        $path .= $file_name;
        return $path;
    }

    public function storeNewVersionFromString(string $content, int $obj_id, string $uuid, int $version, string $extension): string {
        $path = $this->createAndGetPath($obj_id, $uuid) . $version . '.' . $extension;
        $this->dic->logger()->root()->info('Storing as: ' . $path);
        $stream = Streams::ofString($content);
        $web = $this->dic->filesystem()->web();
        $web->writeStream($path, $stream);
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
        if (!$this->dic->filesystem()->web()->hasDir($path)) {
            $this->dic->filesystem()->web()->createDir($path);
        }

        return $path;
    }
}