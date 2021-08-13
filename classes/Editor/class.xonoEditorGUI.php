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
use \ILIAS\DI\Container;
use srag\Plugins\OnlyOffice\CryptoService\WebAccessService;

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
    const CMD_STANDARD = "editFile";

    // TODO: Set correct values gloablly
    const BASE_URL = 'http://192.168.3.103:8080'; // Path to ilias root directory: http://<ILIAS domain>:<PortNr>
    const ONLYOFFICE_URL = 'http://192.168.3.103:3000'; // Path to OnlyOffice Root directory: http://<OO_domain>:<PortNr>

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
        $all_versions = $this->storage_service->getAllVersions($this->file_id);


        $config = $this->buildJSONArray($file, $file_version);
        $token = JwtService::jwtEncode($config, 'secret'); // TODO Define key globally
        $config['token'] = $token;
        $configJson = json_encode($config);
        $configJson = str_replace('"#!!', '', $configJson);
        $configJson = str_replace('!!#"', '', $configJson);

        $historyArray = json_encode($this->buildHistoryArray($this->file_id, $file_version->getFileUuid()));
        $historyArray = str_replace('(\"[{', '("[{', $historyArray);
        $historyArray = str_replace('}]\")', '}]")', $historyArray);
        $historyArray = str_replace('(\"{', '("{', $historyArray);
        $historyArray = str_replace('}\")', '}")', $historyArray);
        $historyArray = str_replace('"#!!', '', $historyArray);
        $historyArray = str_replace('!!#"', '', $historyArray);

        $tpl = $this->plugin->getTemplate('html/tpl.editor.html');
        $tpl->setVariable('BUTTON', $this->plugin->txt('xono_back_button'));
        $tpl->setVariable('SCRIPT_SRC', self::ONLYOFFICE_URL . '/web-apps/apps/api/documents/api.js');
        $tpl->setVariable('CONFIG', $configJson);
        $tpl->setVariable('FILE_TITLE', $file->getTitle());
        $tpl->setVariable('RETURN', $this->generateReturnUrl());
        $tpl->setVariable('LATEST', $file_version->getVersion());
        $tpl->setVariable('HISTORY', $historyArray);
        $tpl->setVariable('HISTORY_DATA', json_encode($this->buildHistoryDataArray($all_versions)));
        $content = $tpl->get();
        echo $content;
        exit;

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
                               "key" => $f->getUuid()->asString() . '-' . $fv->getVersion(),
                               "title" => $f->getTitle(),
                               "url" => self::BASE_URL . ltrim($this->getWACUrl($fv->getUrl()), ".") . '.' . $extension
                         ),
                     "editorConfig" => array("callbackUrl" => self::BASE_URL . '/' . $this->generateCallbackUrl($f->getUuid(),
                             $f->getObjId(), $extension),
                                             "user" => array(
                                                 "id" => $this->dic->user()->getId(),
                                                 "name" => $this->dic->user()->getFullname()
                                             )
                     ),
                     "events" => array("onRequestHistory" => "#!!onRequestHistory!!#",
                                       "onRequestHistoryData" => "#!!onRequestHistoryData!!#"
                     )
        );
    }

    protected function buildHistoryArray(int $file_id, UUID $uuid) : array
    {
        $all_versions = $this->storage_service->getAllVersions($file_id);
        $all_changes = $this->storage_service->getAllChanges($uuid->asString());
        $history_array = array();
        foreach ($all_versions as $version) {
            $v = $version->getVersion();

            $info_array = array(
                "changes" => '#!!JSON.parse("' . $all_changes[$v]->getChangesObjectString() . '")!!#',
                "created" => rtrim($version->getCreatedAt()->__toString(), '<br>'),
                "key" => $uuid->asString() . '-' . $version->getVersion(),
                "serverVersion" => $all_changes[$v]->getServerVersion(),
                "user" => array("id" => $version->getUserId(),
                                "name" => $this->getUserName($version->getUserId()) // ToDo: How to determine name?
                ),
                "version" => $version->getVersion()
            );
            array_push($history_array, $info_array);
        }
        return $history_array;
    }

    protected function buildUrlArray() : array
    {
        $fileVersions = $this->storage_service->getAllVersions($this->file_id);
        $url = array();
        foreach ($fileVersions as $fv) {
            $old_url = $fv->getUrl();
            $wac_url = ltrim(WebAccessService::getWACUrl($old_url), ".");
            $version = $fv->getVersion();
            $url[$version] = $wac_url;
        }
        return $url;
    }

    protected function buildChangeUrlArray(UUID $uuid) : array
    {
        $result = array();
        $all_changes = $this->storage_service->getAllChanges($uuid->asString());
        foreach ($all_changes as $change) {
            $version = $change->getVersion();
            $url = ltrim(WebAccessService::getWACUrl($change->getChangesUrl()), '.');
            $result[$version] = $url;
        }
        return $result;
    }

    protected function buildHistoryDataArray(array $allVersions) : array
    {
        $result = array();
        foreach ($allVersions as $version) {
            $data_array = array();
            $v = $version->getVersion();
            $uuid = $version->getFileUuid()->asString();
            $change_url = $this->storage_service->getChangeUrl($uuid, $v);

            $data_array['changesUrl'] = self::BASE_URL . ltrim(WebAccessService::getWACUrl($change_url), '.');
            $data_array['key'] = $uuid . '-' . $v;
            if ($v > 1) {
                $data_array['previous'] = $this->buildPreviousArray($version);
            }
            $data_array['url'] = self::BASE_URL . ltrim(WebAccessService::getWACUrl($version->getUrl()), '.');
            $data_array['version'] = $v;

            //Compute JWT
            $token = JwtService::jwtEncode($data_array, "secret"); // ToDo: Set Key globally
            $data_array['token'] = $token;
            $result[$v] = $data_array;

        }
        return $result;
    }

    protected function generateReturnUrl() : string
    {
        $content_gui = new xonoContentGUI($this->dic, $this->plugin, $this->file_id);
        return $this->dic->ctrl()->getLinkTarget($content_gui, xonoContentGUI::CMD_SHOW_VERSIONS);

    }

    protected function determineDocType(string $extension) : string
    {
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

    protected function getUserName(int $user_id)
    {
        return $this->dic->user()->getLoginByUserId($user_id);

    }

    protected function buildPreviousArray(FileVersion $version) : array
    {
        $result = array();
        $previous = $this->storage_service->getPreviousVersion($version->getFileUuid()->asString(),
            $version->getVersion());
        $key = $previous->getFileUuid()->asString() . '-' . $previous->getVersion();
        $result['key'] = $key;
        $url = self::BASE_URL . ltrim(WebAccessService::getWACUrl($previous->getUrl()), '.');
        $result['url'] = $url;
        return $result;
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