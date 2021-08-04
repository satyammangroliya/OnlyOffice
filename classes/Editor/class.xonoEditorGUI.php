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
use \Psr\Http\Message\ResponseInterface;
use \ILIAS\DI\Container;

/**
 * Class xonoEditorGUI
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

    const CMD_EDIT = "editFile";
    const CMD_SAVE = "saveChanges";
    const CMD_POST = 'post';
    const CMD_STANDARD = "editFile";

    // TODO: Set correct values gloablly
    const BASE_URL = 'http://192.168.99.72:8080/'; // Path to ilias root directory: http://<ILIAS domain>:<PortNr>
    const ONLYOFFICE_URL = 'http://192.168.99.72:3000/'; // Path to OnlyOffice Root directory: http://<OO_domain>:<PortNr>

    public function __construct(
        Container $dic,
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
                switch ($cmd) {
                    default:
                        $this->{$cmd}();
                        break;
                }

        }
    }

    protected function editFile()
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

    protected function generateCallbackUrl(UUID $file_uuid, int $file_id, string $extension) : string
    {
        $session = array("session_id" => $GLOBALS['DIC']['ilAuthSession']->getId(), "client_id" => CLIENT_ID);
        $session_jwt = JwtService::jwtEncode(json_encode($session), 'secret'); // TODO Define key globally
        $path = 'Customizing/global/plugins/Services/Repository/RepositoryObject/OnlyOffice/save.php?' .
            'token=' . $session_jwt .
            '&uuid=' . $file_uuid->asString() .
            '&file_id=' . $file_id .
            '&client_id=' . CLIENT_ID .
            '&ext=' . $extension;
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
        $extension = pathinfo($fv->getUrl(), PATHINFO_EXTENSION);
        return array("documentType" => $this->determineDocType($extension),
                     "document" =>
                         array("filetype" => $f->getFileType(),
                               "key" => $f->getUuid()->asString() .'-'. $fv->getVersion(),
                               "title" => $f->getTitle(),
                               "url" => self::BASE_URL . ltrim($this->getWACUrl($fv->getUrl()), "./") . '.' . $extension
                         ),
                     "editorConfig" => array("callbackUrl" => self::BASE_URL . $this->generateCallbackUrl($f->getUuid(),
                             $f->getObjId(), $extension),
                                             "user" => array(
                                                 "id" => $this->dic->user()->getId(),
                                                 "name" => $this->dic->user()->getFullname()
                                             )
                     ),
        );
    }

    protected function determineDocType(string $extension) : string {
        switch ($extension) {
            case "pptx":
            case "fodp":
            case "odp":
            case "otp":
            case "pot":
            case "potm":
            case "potx":
            case "pps":
            case "ppsm":
            case "ppsx":
            case "ppt":
            case "pptm":
                return "slide";
            case "xlsx":
            case "csv":
            case "fods":
            case "ods":
            case "ots":
            case "xls":
            case "xlsm":
            case "xlt":
            case "xltm":
            case "xltx":
                return "cell";
            default:
                return "word";
        }
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