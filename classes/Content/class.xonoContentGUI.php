<?php

use srag\Plugins\OnlyOffice\StorageService\StorageService;
use srag\DIC\OnlyOffice\DIC\DICInterface;
use srag\DIC\OnlyOffice\DICStatic;
use srag\Plugins\OnlyOffice\StorageService\Infrastructure\File\ilDBFileVersionRepository;
use srag\Plugins\OnlyOffice\StorageService\Infrastructure\File\ilDBFileRepository;
use srag\Plugins\OnlyOffice\CryptoService\WebAccessService;


/**
 * Class xonoContentGUI
 * @author            Theodor Truffer <tt@studer-raimann.ch>
 * @ilCtrl_isCalledBy xonoContentGUI: ilObjOnlyOfficeGUI
 */
class xonoContentGUI extends xonoAbstractGUI
{
    // TODO: Set correct values globally
    const BASE_URL = 'http://192.168.99.72:8080'; // Path to ilias root directory: http://<ILIAS domain>:<PortNr>

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

    /**
     * Fetches the information about all versions of a file from the database
     * Renders the GUI for content
     */
    protected function showVersions()
    {
        $fileVersions = $this->storage_service->getAllVersions($this->file_id);
        $file = $this->storage_service->getFile($this->file_id);
        $ext = pathinfo($file->getTitle(), PATHINFO_EXTENSION);
        $fileName = rtrim($file->getTitle(), '.'.$ext);
        $json = json_encode($fileVersions);
        $url = array();
        foreach ($fileVersions as $fv) {
            $old_url = $fv->getUrl();
            $wac_url = ltrim(WebAccessService::getWACUrl($old_url), ".");
            $version = $fv->getVersion();
            $url[$version] = $wac_url;
        }

        $tpl = $this->plugin->getTemplate('html/tpl.file_history.html');
        $tpl->setVariable('TBL_TITLE', "Document History");
        $tpl->setVariable('TBL_DATA', $json);
        $tpl->setVariable('BASE_URL', self::BASE_URL);
        $tpl->setVariable('URL', json_encode($url));
        $tpl->setVariable('FILENAME', $fileName);
        $tpl->setVariable('EXTENSION', $ext);
        $tpl->setVariable('VERSION', $this->plugin->txt('xono_version'));
        $tpl->setVariable('CREATED', $this->plugin->txt('xono_date'));
        $tpl->setVariable('EDITOR', $this->plugin->txt('xono_editor'));
        $tpl->setVariable('DOWNLOAD', $this->plugin->txt('xono_download'));
        $content = $tpl->get();
        $this->dic->ui()->mainTemplate()->setContent($content);
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