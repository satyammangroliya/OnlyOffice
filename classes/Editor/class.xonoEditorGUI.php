<?php

use srag\Plugins\OnlyOffice\StorageService\DTO\File;
use srag\Plugins\OnlyOffice\StorageService\DTO\FileVersion;
use srag\Plugins\OnlyOffice\StorageService\Infrastructure\Common\UUID;
use srag\Plugins\OnlyOffice\StorageService\StorageService;
use srag\DIC\OnlyOffice\DIC\DICInterface;
use srag\DIC\OnlyOffice\DICStatic;
use srag\Plugins\OnlyOffice\StorageService\Infrastructure\File\ilDBFileVersionRepository;
use srag\Plugins\OnlyOffice\StorageService\Infrastructure\File\ilDBFileRepository;
use srag\Plugins\OnlyOffice\StorageService\Infrastructure\File\ilDBFileChangeRepository;
use srag\Plugins\OnlyOffice\StorageService\InfoService;
use srag\Plugins\OnlyOffice\CryptoService\JwtService;
use \ILIAS\DI\Container;
use srag\Plugins\OnlyOffice\CryptoService\WebAccessService;


define('baseurl', InfoService::getBaseUrl());
define('oo_url', InfoService::getOnlyOfficeUrl());
define('secret', InfoService::getSecret());


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

    const BASE_URL = baseurl;
    const ONLYOFFICE_URL = oo_url;
    const ONLYOFFICE_KEY = secret;

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
            new ilDBFileRepository(),
            new ilDBFileChangeRepository()
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
        $all_versions = $this->storage_service->getAllVersions($this->file_id);
        $latest_version = $this->storage_service->getLatestVersions($file->getUuid());

        $tpl = $this->plugin->getTemplate('html/tpl.editor.html');
        $tpl->setVariable('FILE_TITLE', $file->getTitle());
        $tpl->setVariable('BUTTON', $this->plugin->txt('xono_back_button'));
        $tpl->setVariable('SCRIPT_SRC', self::ONLYOFFICE_URL . '/web-apps/apps/api/documents/api.js');
        $tpl->setVariable('CONFIG', $this->config($file, $latest_version));
        $tpl->setVariable('RETURN', $this->generateReturnUrl());
        $tpl->setVariable('LATEST', $latest_version->getVersion());
        $tpl->setVariable('HISTORY', $this->history($latest_version, $all_versions));
        $tpl->setVariable('HISTORY_DATA', $this->historyData($all_versions));
        $content = $tpl->get();
        echo $content;
        exit;

    }

    /**
     * Builds and returns the config array as string
     *
     * @param File        $file
     * @param FileVersion $fileVersion
     * @return string
     */
    protected function config(File $file, FileVersion $fileVersion) : string
    {
        $as_array = array(); // Config Array
        $extension = pathinfo($fileVersion->getUrl(), PATHINFO_EXTENSION);

        // general config
        $as_array['documentType'] = $this->determineDocType($extension);

        // document config
        $document = array(); // SubArray "document"
        $document['fileType'] = $file->getFileType();
        $document['key'] = $this->generateDocumentKey($fileVersion);
        $document['title'] = $file->getTitle();
        $document['url'] = self::BASE_URL . ltrim(WebAccessService::getWACUrl($fileVersion->getUrl()), ".");
        $as_array['document'] = $document;

        // editor config
        $editor = array();
        $editor['callbackUrl'] = $this->generateCallbackUrl($file->getUuid(),
            $file->getObjId(), $extension);
        $editor['user'] = $this->buildUserArray($this->dic->user()->getId());
        $editor['mode'] = $this->determineAccessRights();
        $as_array['editorConfig'] = $editor;

        // events config
        $as_array['events'] = array("onRequestHistory" => "#!!onRequestHistory!!#",
                                    "onRequestHistoryData" => "#!!onRequestHistoryData!!#"
        );

        // add token
        $token = JwtService::jwtEncode($as_array, self::ONLYOFFICE_KEY);
        $as_array['token'] = $token;

        // convert to valid string
        $result = json_encode($as_array);
        $result = str_replace('"#!!', '', $result);
        $result = str_replace('!!#"', '', $result);
        return $result;

    }

    /**
     * Builds and returns an array containing the version history of a file as string
     *
     * @param FileVersion $latestVersion
     * @param array       $all_versions
     * @return string
     */
    protected function history(FileVersion $latestVersion, array $all_versions) : string
    {
        $all_changes = $this->storage_service->getAllChanges($latestVersion->getFileUuid()->asString());
        $history_array = array();

        // add all versions to history
        foreach ($all_versions as $version) {
            $v = $version->getVersion();
            $info_array = array(
                "changes" => '#!!JSON.parse("' . $all_changes[$v]->getChangesObjectString() . '")!!#',
                "created" => rtrim($version->getCreatedAt()->__toString(), '<br>'),
                "key" => $this->generateDocumentKey($version),
                "serverVersion" => $all_changes[$v]->getServerVersion(),
                "user" => $this->buildUserArray($version->getUserId()),
                "version" => $version->getVersion()
            );
            array_push($history_array, $info_array);
        }

        // convert to valid string
        $result = json_encode($history_array);
        $result = str_replace('(\"[{', '("[{', $result);
        $result = str_replace('}]\")', '}]")', $result);
        $result = str_replace('(\"{', '("{', $result);
        $result = str_replace('}\")', '}")', $result);
        $result = str_replace('"#!!', '', $result);
        $result = str_replace('!!#"', '', $result);

        return $result;
    }

    /**
     * Builds and returns an array containing information about all versions as string
     * @param array $allVersions
     * @return string
     */
    protected function historyData(array $allVersions) : string
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

            //token
            $token = JwtService::jwtEncode($data_array, self::ONLYOFFICE_KEY);
            $data_array['token'] = $token;
            $result[$v] = $data_array;

        }
        return json_encode($result);
    }

    /* --- Helper Methods --- */
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

    protected function generateCallbackUrl(UUID $file_uuid, int $file_id, string $extension) : string
    {
        $session = array("session_id" => $GLOBALS['DIC']['ilAuthSession']->getId(), "client_id" => CLIENT_ID);
        $session_jwt = JwtService::jwtEncode(json_encode($session), self::ONLYOFFICE_KEY);
        $path = 'Customizing/global/plugins/Services/Repository/RepositoryObject/OnlyOffice/save.php?' .
            'token=' . $session_jwt .
            '&uuid=' . $file_uuid->asString() .
            '&file_id=' . $file_id .
            '&client_id=' . CLIENT_ID .
            '&ext=' . $extension;
        return self::BASE_URL . '/' . $path;
    }

    protected function generateDocumentKey(FileVersion $fv) : string
    {
        return $fv->getFileUuid()->asString() . '-' . $fv->getVersion();
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

    protected function buildUserArray(int $user_id) : array
    {
        $user = new ilObjUser($user_id);
        return array("id" => $user_id, "name" => $user->getPublicName());
    }

    protected function determineAccessRights(): string {
        if (ilObjOnlyOfficeAccess::hasWriteAccess())
            return "edit";
        else
            return "view";
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