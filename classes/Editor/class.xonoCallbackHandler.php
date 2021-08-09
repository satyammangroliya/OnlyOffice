<?php

require_once 'Customizing/global/plugins/Services/Repository/RepositoryObject/OnlyOffice/vendor/autoload.php';
require_once 'libs/composer/vendor/autoload.php';

use ILIAS\DI\Container;
use srag\Plugins\OnlyOffice\StorageService\StorageService;
use srag\Plugins\OnlyOffice\StorageService\Infrastructure\File\ilDBFileVersionRepository;
use srag\Plugins\OnlyOffice\StorageService\Infrastructure\File\ilDBFileRepository;
use Matrix\Exception;

/**
 * Class xonoCallbackHandler
 * @author Sophie Pfister <sophie@fluxlabs.ch>
 */
class xonoCallbackHandler
{
    const CMD_HANDLE_CALLBACK = "handleCallback";

    /** @var $dic Container */
    protected $dic;
    /** @var $storage_service StorageService */
    protected $storage_service;
    /** @var $data string */
    protected $data;
    /** @var $uuid string */
    protected $uuid;
    /** @var $file_id int */
    protected $file_id;
    /** @var $editor_id int */
    protected $editor_id;
    /** @var $extension string */
    protected $file_extension;
    /** @var $changes_object string */
    protected $changes_object;
    /** @var $serverVersion string */
    protected $serverVersion;
    /** @var $change_content string */
    protected $change_content;
    /** @var $change_extension string */
    protected $change_extension;

    public function __construct(
        Container $dic,
        string $data,
        string $uuid,
        int $file_id,
        int $editor_id,
        string $file_extension,
        string $changes_object,
        string $serverVersion,
        string $change_content,
        string $change_extension
    ) {
        $this->dic = $dic;
        $this->data = $data;
        $this->uuid = $uuid;
        $this->file_id = $file_id;
        $this->editor_id = $editor_id;
        $this->file_extension = $file_extension;
        $this->changes_object = $changes_object;
        $this->serverVersion = $serverVersion;
        $this->change_content = $change_content;
        $this->change_extension = $change_extension;
        $this->afterConstructor();
        $this->dic->logger()->root()->info("Callback Handler Constructed");

    }

    protected function afterConstructor()/*: void*/
    {
        $this->storage_service = new StorageService(
            $this->dic,
            new ilDBFileVersionRepository(),
            new ilDBFileRepository()
        );
    }

    public function handleCallback() : bool
    {
        try {
            $this->storage_service->updateFileFromUpload($this->data, $this->file_id, $this->uuid, $this->editor_id,
                $this->file_extension, $this->changes_object, $this->serverVersion, $this->change_content,
                $this->change_extension);
            $this->dic->logger()->root()->info("File saved");
            return true;
        } catch (Exception $e) {
            return false;
        }

    }
}