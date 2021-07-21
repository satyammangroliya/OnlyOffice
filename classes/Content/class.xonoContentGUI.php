<?php

use srag\Plugins\OnlyOffice\StorageService\StorageService;
use srag\DIC\OnlyOffice\DIC\DICInterface;
use srag\DIC\OnlyOffice\DICStatic;
use srag\Plugins\OnlyOffice\StorageService\Infrastructure\File\ilDBFileVersionRepository;
use srag\Plugins\OnlyOffice\StorageService\Infrastructure\File\ilDBFileRepository;
use srag\Plugins\OnlyOffice\StorageService\DTO\FileVersion;
use srag\Plugins\OnlyOffice\StorageService\DTO\File;

/**
 * Class xonoContentGUI
 * @author            Theodor Truffer <tt@studer-raimann.ch>
 * @ilCtrl_isCalledBy xonoContentGUI: ilObjOnlyOfficeGUI
 */
class xonoContentGUI extends xonoAbstractGUI
{

    /**
     * @var ilOnlyOfficePlugin
     */
    protected
        $plugin;
    /**
     * @var StorageService
     */
    protected
        $storage_service;
    /**
     * @var int
     */
    protected
        $file_id;

    const CMD_STANDARD = 'index';
    const CMD_SHOW_VERSIONS = 'showVersions';
    const CMD_EDIT = 'edit';

    // ToDo: Set correct values
    const BASE_URL = 'http://localhost:8080';
    const CALLBACK_URL = 'http://localhost:8080/callback';
    const ONLYOFFICE_URL = 'http://localhost:3000/';

    public
    function __construct(
        \ILIAS\DI\Container $dic,
        ilOnlyOfficePlugin $plugin,
        int $object_id
    ) {
        parent::__construct($dic, $plugin);
        $this->file_id = $object_id;
        $this->afterConstructor();
    }

    protected
    function afterConstructor()/*: void*/
    {

        $this->storage_service = new StorageService(
            self::dic()->dic(),
            new ilDBFileVersionRepository(),
            new ilDBFileRepository()
        );
    }

    public

    final function getType() : string
    {
        return ilOnlyOfficePlugin::PLUGIN_ID;
    }

    public function executeCommand()
    {
        self::dic()->help()->setScreenIdComponent(ilOnlyOfficePlugin::PLUGIN_ID);
        $next_class = $this->dic->ctrl()->getNextClass($this);
        $cmd = $this->dic->ctrl()->getCmd(self::CMD_STANDARD);
        //$cmd = self::CMD_STANDARD;

        switch ($next_class) {
            default:
                $this->$cmd();
                break;
        }
    }

    protected function showVersions()
    {
        $fileVersions = $this->storage_service->getAllVersions($this->file_id);
        $json = json_encode($fileVersions);

        $tpl = $this->plugin->getTemplate('html/tpl.file_history.html');
        $tpl->setVariable('TBL_TITLE', "Document History");
        $tpl->setVariable('TBL_DATA', $json);

        $content = $tpl->get();
        $this->dic->ui()->mainTemplate()->setContent($content);
    }

    protected function edit()
    {
        $file = $this->storage_service->getFile($this->file_id);
        $file_version = $this->storage_service->getLatestVersions($file->getUuid());
        $arrayWithoutToken = $this->buildJSONArray($file, $file_version);
        $token = $this->jwtEncode($arrayWithoutToken);
        $arrayWithToken = $this->buildJSONArray($file, $file_version, $token);
        $configJson = json_encode($arrayWithToken);

        $tpl = $this->plugin->getTemplate('html/tpl.editor.html');
        $tpl->setVariable('SCRIPT_SRC', self::ONLYOFFICE_URL . '/web-apps/apps/api/documents/api.js');
        $tpl->setVariable('CONFIG', $configJson);
        $content = $tpl->get();
        //$content = '<h1> Edit file number ' . $this->file_id . '</h1>';
        $this->dic->ui()->mainTemplate()->setContent($content);

    }

    protected function getWACUrl(string $url)
    {
        ilWACSignedPath::setTokenMaxLifetimeInSeconds(ilWACSignedPath::MAX_LIFETIME);
        $file_path = ilWACSignedPath::signFile(ilUtil::getWebspaceDir() . $url);
        $file_path .= '&' . ilWebAccessChecker::DISPOSITION . '=' . ilFileDelivery::DISP_ATTACHMENT;
        return $file_path;

    }

    /**
     * Get DIC interface
     * @return DICInterface DIC interface
     */
    protected static final function dic() : DICInterface
    {
        return DICStatic::dic();
    }

    protected function buildJSONArray(File $f, FileVersion $fv, string $token = '') : array
    {
/*        if ($token == '') {
            return array("documentType" => "word",
                         "document" =>
                             array("filetype" => $f->getFileType(),
                                   "key" => $f->getUuid()->asString(),
                                   "title" => $f->getTitle(),
                                   "url" => self::BASE_URL . $this->getWACUrl($fv->getUrl())
                             ),
                         "editorConfig" => array("callbackUrl" => self::CALLBACK_URL . $fv->getUrl()),
                         "user" => array(
                             "id" => $this->dic->user()->getId(),
                             "name" => $this->dic->user()->getPublicName()
                         )
            );
        } else {*/
            return array("documentType" => "word",
                         "token" => $token,
                         "document" =>
                             array("filetype" => $f->getFileType(),
                                   "key" => $f->getUuid()->asString(),
                                   "title" => $f->getTitle(),
                                   "url" => self::BASE_URL . $this->getWACUrl($fv->getUrl())
                             ),
                         "editorConfig" => array("callbackUrl" => self::CALLBACK_URL . $fv->getUrl(),
                                                 "user" => array(
                                                     "id" => $this->dic->user()->getId(),
                                                     "name" => $this->dic->user()->getPublicName()
                                                 )
                         ),
            );

        //}

    }

    protected function jwtEncode($payload)
    {
        $header = [
            "alg" => "HS256",
            "typ" => "JWT"
        ];
        $encHeader = $this->base64UrlEncode(json_encode($header));
        $encPayload = $this->base64UrlEncode(json_encode($payload));
        $hash = $this->base64UrlEncode($this->calculateHash($encHeader, $encPayload));

        return "$encHeader.$encPayload.$hash";
    }

    function jwtDecode($token)
    {
        if (!$this->isJwtEnabled()) {
            return "";
        }

        $split = explode(".", $token);
        if (count($split) != 3) {
            return "";
        }

        $hash = $this->base64UrlEncode($this->calculateHash($split[0], $split[1]));

        if (strcmp($hash, $split[2]) != 0) {
            return "";
        }
        return $this->base64UrlDecode($split[1]);
    }

    protected function calculateHash($encHeader, $encPayload)
    {
        return hash_hmac("sha256", "$encHeader.$encPayload", "secret", true);
    }

    protected function base64UrlEncode($str)
    {
        return str_replace("/", "_", str_replace("+", "-", trim(base64_encode($str), "=")));
    }

    protected function base64UrlDecode($payload)
    {
        $b64 = str_replace("_", "/", str_replace("-", "+", $payload));
        switch (strlen($b64) % 4) {
            case 2:
                $b64 = $b64 . "==";
                break;
            case 3:
                $b64 = $b64 . "=";
                break;
        }
        return base64_decode($b64);
    }
}