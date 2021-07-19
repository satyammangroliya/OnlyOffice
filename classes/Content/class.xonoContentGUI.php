<?php

use srag\Plugins\OnlyOffice\StorageService\StorageService;
use srag\DIC\OnlyOffice\DIC\DICInterface;
use srag\DIC\OnlyOffice\DICStatic;
use srag\Plugins\OnlyOffice\StorageService\Infrastructure\File\ilDBFileVersionRepository;
use srag\Plugins\OnlyOffice\StorageService\Infrastructure\File\ilDBFileRepository;
use srag\Plugins\OnlyOffice\UI\FileVersionRenderer;
use srag\Plugins\OnlyOffice\StorageService\DTO\FileVersion;

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
    protected $plugin;
    /**
     * @var StorageService
     */
    protected $storage_service;
    /**
     * @var int
     */
    protected $file_id;

    const CMD_STANDARD = 'index';
    const CMD_SHOW_VERSIONS = 'showVersions';

    public function __construct(\ILIAS\DI\Container $dic, ilOnlyOfficePlugin $plugin, int $object_id)
    {
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
        //$cmd = self::CMD_STANDARD;

        switch ($next_class) {
            default:
                $this->$cmd();
                break;
        }
    }

    /**
     *
     */
    protected function index()
    {
        $tpl = $this->plugin->getTemplate('html/tpl.file_history.html');
        $tpl->setVariable('SCRIPT_SRC', 'http://localhost/web-apps/apps/api/documents/api.js');
        //$config = new stdClass();
        //$config->document =
        $tpl_get = $tpl->get();
        $this->dic->ui()->mainTemplate()->setContent($tpl_get);
        $this->dic->ui()->mainTemplate()->printToStdOut();
        '    config = {
        "document": {
            "fileType": "docx",
            "key": "Khirz6zTPdfd7",
            "title": "Example Document Title.docx",
            "url": "https://example.com/url-to-example-document.docx"
        },
        "documentType": "text",
        "editorConfig": {
            "callbackUrl": "https://example.com/url-to-callback.ashx",
             "user": {
                "id": "F89d8069ba2b",
                "name": "Kate Cage"
            } 
        },
        ,
    "editorConfig": {

    }
    };';
    }

    protected function showVersions()
    {
        $fileVersions = $this->storage_service->getAllVersions($this->file_id);
        $size = sizeof($fileVersions);
        $fvJSON = '[';
        $counter = 1;
        foreach ($fileVersions as $fv) {
            if ($counter != 1)
                $fvJSON .= ', ';
            $counter++;
            $fvJSON .= '{';
            $fvJSON .= '"fileVersion": '. $fv->getVersion(). ', "createdAt": "DATUM", "editorId": '. $fv->getUserId() . '}';
        }
        $fvJSON .= ']';

        //$fileVersionsString = '';
        //$r = new FileVersionRenderer($this->dic, $this->file_id,  $fvArray);
        //$content = $r->renderUglyTable();
        //$content = $r->renderReactTable();
        $tpl = $this->plugin->getTemplate('html/tpl.file_history.html');
        $tpl->setVariable('TBL_TITLE', "Document History");
        $tpl->setVariable('TBL_DATA', $fvJSON);

        $content = $tpl->get();
        $this->dic->ui()->mainTemplate()->setContent($content);

    }

    /**
     * Build config.json to pass to the editor.
     * TODO: Verify whether this works
     * @return string
     */
    protected function configBuilder() : string
    {
        $file_version = $this->storage_service->getFileVersion($this->file_id);
        $file = $this->storage_service->getFile($file_version->getFileUuid());

        // Define parameters for field "document"
        $fileType = $file->getFileType();
        $key = $file->getUuid();
        $title = $file->getTitle();
        $url = $file_version->getUrl();

        // Define parameters for field "editor"
        //$callbackURL =
        $mode = "edit";
        if (!ilObjOnlyOfficeAccess::hasWriteAccess()) {
            $mode = "view";
        }
        $user_id = $this->dic->user()->getId();
        $name = $this->dic->user()->getFirstname() . $this->dic->user()->getLastname();

        $config = '{
            "document": {
                "fileType": ' . $fileType . ',
                "key": ' . $key . ',
                "title": ' . $title . ',
                "url": ' . $url . ',
                }
            "editorConfig: {
                "mode": ' . $mode . ',
                "user": {
                    "id": ' . $user_id . ',
                    "name": ' . $name . '}
                }
            }';
        return $config;

    }

    /**
     * Get DIC interface
     * @return DICInterface DIC interface
     */
    protected static final function dic() : DICInterface
    {
        return DICStatic::dic();
    }

    protected function buildJSONforFileVesion(FileVersion $fv) : string
    {
        $json = '{
        Version: ' . $fv->getVersion() . ',
        CreatedAt: ' . $fv->getCreatedAt() . ',
        UserID: ' . $fv->getUserId() . ',
        URL: ' . $fv->getUrl() . '
        }';
        // 'UUID ' . $fv->getFileUuid() .'
        return $json;

    }
}