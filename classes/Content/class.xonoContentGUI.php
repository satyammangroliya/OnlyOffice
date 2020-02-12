<?php

/**
 * Class xonoContentGUI
 *
 * @author Theodor Truffer <tt@studer-raimann.ch>
 *
 * @ilCtrl_isCalledBy xonoContentGUI: ilObjOnlyOfficeGUI
 */
class xonoContentGUI extends xonoAbstractGUI
{

    const CMD_STANDARD = 'index';


    /**
     *
     */
    public function executeCommand()
    {
        $next_class = $this->dic->ctrl()->getNextClass($this);
        $cmd = $this->dic->ctrl()->getCmd(self::CMD_STANDARD);

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
        $tpl = $this->plugin->getTemplate('tpl.editor.html');
        $tpl->setVariable('SCRIPT_SRC', 'http://localhost/web-apps/apps/api/documents/api.js');
        $config = new stdClass();
        $config->document =
        $this->dic->ui()->mainTemplate()->setContent($tpl->get());
        $this->dic->ui()->mainTemplate()->show();
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
}