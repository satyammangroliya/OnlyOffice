<?php

namespace srag\Plugins\OnlyOffice\StorageService;

use ilDateTime;
use ilDateTimeException;
use ILIAS\DI\Container;
use ILIAS\Filesystem\Exception\IOException;
use ILIAS\FileUpload\DTO\UploadResult;
use srag\Plugins\OnlyOffice\StorageService\DTO\File;
use srag\Plugins\OnlyOffice\StorageService\DTO\FileVersion;
use srag\Plugins\OnlyOffice\StorageService\FileSystem\FileSystemService;
use srag\Plugins\OnlyOffice\StorageService\Infrastructure\Common\UUID;
use srag\Plugins\OnlyOffice\StorageService\Infrastructure\File\FileRepository;
use srag\Plugins\OnlyOffice\StorageService\Infrastructure\File\FileVersionRepository;

/**
 * Class StorageService
 *
 * @package srag\Plugins\OnlyOffice\StorageService
 *
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class StorageService
{
    /**
     * @var Container
     */
    protected $dic;
    /**
     * @var FileVersionRepository
     */
    protected $file_version_repository;
    /**
     * @var FileSystemService
     */
    protected $file_system_service;
    /**
     * @var FileRepository
     */
    protected $file_repository;


    /**
     * StorageService constructor.
     *
     * @param Container             $dic
     * @param FileVersionRepository $file_version_repository
     * @param FileRepository        $file_repository
     */
    public function __construct(Container $dic, FileVersionRepository $file_version_repository, FileRepository $file_repository)
    {
        $this->dic = $dic;
        $this->file_version_repository = $file_version_repository;
        $this->file_repository = $file_repository;
        $this->file_system_service = new FileSystemService($dic);
    }


    /**
     * @param UploadResult $upload_result
     * @param int          $obj_id
     *
     * @return File
     * @throws IOException
     * @throws ilDateTimeException
     */
    public function createNewFileFromUpload(UploadResult $upload_result, int $obj_id) : File
    {
        $new_file_id = new UUID();
        $this->file_system_service->storeUpload($upload_result, $obj_id, $new_file_id->asString());
        $this->file_repository->create($new_file_id, $obj_id, $upload_result->getName(), $upload_result->getMimeType());
        $created_at = new ilDateTime(time(), IL_CAL_UNIX);
        $version = $this->file_version_repository->create($new_file_id, $this->dic->user()->getId(), $created_at);

        $file_version = new FileVersion($version, $created_at, $this->dic->user()->getId(), '');
        $file = new File(
            $new_file_id,
            $obj_id,
            $upload_result->getName(),
            $upload_result->getMimeType(),
            [$file_version]
        );

        return $file;
    }
}