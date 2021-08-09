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
use srag\Plugins\OnlyOffice\StorageService\Infrastructure\File\ilDBFileChangeRepository;
use srag\Plugins\OnlyOffice\StorageService\Infrastructure\File\FileChangeRepository;

/**
 * Class StorageService
 * @package srag\Plugins\OnlyOffice\StorageService
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

    protected static $changeId = 1;

    /**
     * StorageService constructor.
     * @param Container             $dic
     * @param FileVersionRepository $file_version_repository
     * @param FileRepository        $file_repository
     */
    public function __construct(
        Container $dic,
        FileVersionRepository $file_version_repository,
        FileRepository $file_repository
    ) {
        $this->dic = $dic;
        $this->file_version_repository = $file_version_repository;
        $this->file_repository = $file_repository;
        $this->file_system_service = new FileSystemService($dic);
    }


    /**
     * @param UploadResult $upload_result
     * @param int          $obj_id
     * @return File
     * @throws IOException
     * @throws ilDateTimeException
     */
    public function createNewFileFromUpload(UploadResult $upload_result, int $obj_id) : File
    {
        $new_file_id = new UUID();
        $path = $this->file_system_service->storeUploadResult($upload_result, $obj_id, $new_file_id->asString());
        $this->file_repository->create($new_file_id, $obj_id, $upload_result->getName(), $upload_result->getMimeType());
        $created_at = new ilDateTime(time(), IL_CAL_UNIX);
        $version = $this->file_version_repository->create($new_file_id, $this->dic->user()->getId(), $created_at,
            $path);

        $file_version = new FileVersion($version, $created_at, $this->dic->user()->getId(), $path, $new_file_id);
        $file = new File($new_file_id, $obj_id, $upload_result->getName(), $upload_result->getMimeType());
        return $file;
    }

    public function updateFileFromUpload(
        string $content,
        int $file_id,
        string $uuid_string,
        int $editor_id,
        string $file_extension,
        string $changes_object,
        string $serverVersion,
        string $change_content,
        string $change_extension
    ) : FileVersion {
        // Store FileVersion and Create Database Entry
        $uuid = new UUID($uuid_string);
        $created_at = new ilDateTime(time(), IL_CAL_UNIX);
        $version = $this->getLatestVersions($uuid)->getVersion() + 1;
        $this->dic->logger()->root()->info("Version: " . $version);
        $path = $this->file_system_service->storeNewVersionFromString($content, $file_id, $uuid_string, $version,
            $file_extension);
        $version = $this->file_version_repository->create($uuid, $editor_id, $created_at, $path);

        //Store Changes and Create Database Entry
        $file_change_repository = new ilDBFileChangeRepository();
        $change_path = $this->file_system_service->storeChanges($change_content, $file_id, $uuid_string, $version,
            $change_extension);
        $id = $file_change_repository->getNextId(); // ToDo: Do this in a better way
        $file_change_repository->create($id, $uuid, $version, $changes_object, $serverVersion,
            $change_path);

        // Return FileVersion object
        $fileVersion = new FileVersion($version, $created_at, $editor_id, $path, $uuid);
        return $fileVersion;
    }


    public function getAllVersions(int $object_id) : array
    {
        $file = $this->file_repository->getFile($object_id);
        return $this->file_version_repository->getAllVersions($file->getFileUuid());
    }

    public function getFile(int $file_id) : File
    {
        return $this->file_repository->getFile($file_id);
    }

    public function getLatestVersions(UUID $file_uuid) : FileVersion
    {
        return $this->file_version_repository->getLatestVersion($file_uuid);
    }


}