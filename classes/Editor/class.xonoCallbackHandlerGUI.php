<?php

require_once 'Customizing/global/plugins/Services/Repository/RepositoryObject/OnlyOffice/vendor/autoload.php';
require_once 'libs/composer/vendor/autoload.php';

use ILIAS\DI\Container;
use srag\Plugins\OnlyOffice\StorageService\StorageService;
use srag\Plugins\OnlyOffice\StorageService\Infrastructure\File\ilDBFileVersionRepository;
use srag\Plugins\OnlyOffice\StorageService\Infrastructure\File\ilDBFileRepository;
use Matrix\Exception;

/**
 * Class callbackHandlerGUI
 * @author Sophie Pfister <sophie@fluxlabs.ch>
 */
class xonoCallbackHandlerGUI
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

    public function __construct(Container $dic, string $data, string $uuid, int $file_id, int $editor_id)
    {
        $this->dic = $dic;
        $this->data = $data;
        $this->uuid = $uuid;
        $this->file_id = $file_id;
        $this->editor_id = $editor_id;
        $this->afterConstructor();
        $this->dic->logger()->root()->info("Callback Handler Constructed");

    }

    public function executeCommand()
    {
        $next_class = $this->dic->ctrl()->getNextClass($this);
        $cmd = $this->dic->ctrl()->getCmd(self::CMD_HANDLE_CALLBACK);

        switch ($next_class) {
            default:
                switch ($cmd) {
                    default:
                        $this->{$cmd}();
                        break;
                }
        }
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
            $this->storage_service->updateFileFromUpload($this->data, $this->file_id, $this->uuid, $this->editor_id);
            $this->dic->logger()->root()->info("File saved");
            return true;
        } catch (Exception $e) {
            return false;
        }

    }
}