<?php

use srag\Plugins\OnlyOffice\StorageService\DTO\File;
use srag\Plugins\OnlyOffice\StorageService\DTO\FileVersion;
use srag\Plugins\OnlyOffice\StorageService\Infrastructure\Common\UUID;
use srag\Plugins\OnlyOffice\StorageService\StorageService;
use srag\DIC\OnlyOffice\DIC\DICInterface;
use srag\DIC\OnlyOffice\DICStatic;
use srag\Plugins\OnlyOffice\StorageService\Infrastructure\File\ilDBFileVersionRepository;
use srag\Plugins\OnlyOffice\StorageService\Infrastructure\File\ilDBFileRepository;
use srag\Plugins\OnlyOffice\CryptoService\JwtService;

/**
 * Class xonoEditorGUI
 *
 * @author              Sophie Pfister <sophie@fluxlabs.ch>
 * @ilCtrl_IsCalledBy   xonoEditorGUI: ilObjOnlyOfficeGUI
 */


class xonoEditorGUI extends xonoAbstractGUI
{
    /**
     * @var ilOnlyOfficePlugin
     */
    protected $plugin;
    /**
     * @var StorageService
     */
    protected $storage_service;
    /**
     * @var int
     */
    protected $file_id;

    const CMD_EDIT = "edit";
    const CMD_SAVE = "save";
    const CMD_STANDARD = "edit";

    // TODO: Set correct values
    const BASE_URL = 'http://localhost:8080';
    const ONLYOFFICE_URL = 'http://localhost:3000/';

    public function __construct(
        \ILIAS\DI\Container $dic,
        ilOnlyOfficePlugin $plugin,
        int $object_id
    ) {
        parent::__construct($dic, $plugin);
        $this->file_id = $object_id;
        $this->afterConstructor();
    }

    protected function afterConstructor()/*: void*/
    {

        $this->storage_service = new StorageService(
            self::dic()->dic(),
            new ilDBFileVersionRepository(),
            new ilDBFileRepository()
        );
    }

    public final function getType() : string
    {
        return ilOnlyOfficePlugin::PLUGIN_ID;
    }

    public function executeCommand()
    {
        self::dic()->help()->setScreenIdComponent(ilOnlyOfficePlugin::PLUGIN_ID);
        $next_class = $this->dic->ctrl()->getNextClass($this);
        $cmd = $this->dic->ctrl()->getCmd(self::CMD_STANDARD);

        switch ($next_class) {
            default:
                $this->{$cmd}();
                break;
        }
    }

    protected function edit()
    {
        $file = $this->storage_service->getFile($this->file_id);
        $file_version = $this->storage_service->getLatestVersions($file->getUuid());
        $config = $this->buildJSONArray($file, $file_version);
        $token = JwtService::jwtEncode($config, 'secret'); // TODO Define key globally
        $config['token'] = $token;
        $configJson = json_encode($config);

        $tpl = $this->plugin->getTemplate('html/tpl.editor.html');
        $tpl->setVariable('SCRIPT_SRC', self::ONLYOFFICE_URL . '/web-apps/apps/api/documents/api.js');
        $tpl->setVariable('CONFIG', $configJson);
        $content = $tpl->get();
        $this->dic->ui()->mainTemplate()->setContent($content);

    }

    protected function save() {
        $params = $this->dic->ctrl()->getParameterArray($this);
        $uuid = $params['uuid'];
        $file_id = $params['file_id'];
        $editor = $params['editor_id'];
        $status = filter_input(INPUT_POST, "status");
        if ($status == 2) {
            $result = end($this->dic->upload()->getResults());
            $this->storage_service->updateFileFromUpload($result, $file_id, $uuid, $editor);
        }

    }

    protected function generateCallbackUrl(UUID $file_uuid, int $file_id) :string
    {
        $this->dic->ctrl()->setParameter($this, "uuid", $file_uuid->asString());
        $this->dic->ctrl()->setParameter($this, "file_id", $file_id);
        $this->dic->ctrl()->setParameter($this, "editor_id", $this->dic->user()->getId());
        $path = $this->dic->ctrl()->getLinkTarget($this, self::CMD_SAVE);
        return $path;
    }

    protected function getWACUrl(string $url) : string
    {
        ilWACSignedPath::setTokenMaxLifetimeInSeconds(ilWACSignedPath::MAX_LIFETIME);
        $file_path = ilWACSignedPath::signFile(ilUtil::getWebspaceDir() . $url);
        $file_path .= '&' . ilWebAccessChecker::DISPOSITION . '=' . ilFileDelivery::DISP_ATTACHMENT;
        return $file_path;

    }

    protected function buildJSONArray(File $f, FileVersion $fv) : array
    {
        return array("documentType" => "word",
                     "document" =>
                         array("filetype" => $f->getFileType(),
                               "key" => $f->getUuid()->asString(),
                               "title" => $f->getTitle(),
                               "url" => self::BASE_URL . ltrim($this->getWACUrl($fv->getUrl()), ".")
                         ),
                     "editorConfig" => array("callbackUrl" => $this->generateCallbackUrl($f->getUuid(), $f->getObjId()),
                                             "user" => array(
                                                 "id" => $this->dic->user()->getId(),
                                                 "name" => $this->dic->user()->getPublicName()
                                             )
                     ),
        );
    }

    /**
     * Get DIC interface
     * @return DICInterface DIC interface
     */
    protected static final function dic() : DICInterface
    {
        return DICStatic::dic();
    }
}