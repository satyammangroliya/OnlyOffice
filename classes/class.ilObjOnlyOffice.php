<?php

use srag\Plugins\OnlyOffice\ObjectSettings\ObjectSettings;
use srag\Plugins\OnlyOffice\Utils\OnlyOfficeTrait;
use srag\DIC\OnlyOffice\DICTrait;
use srag\Plugins\OnlyOffice\StorageService\Infrastructure\File\ilDBFileRepository;
use srag\Plugins\OnlyOffice\StorageService\Infrastructure\File\ilDBFileVersionRepository;
use srag\Plugins\OnlyOffice\StorageService\Infrastructure\File\ilDBFileChangeRepository;

/**
 * Class ilObjOnlyOffice
 * Generated by SrPluginGenerator v1.3.4
 * @author Sophie Pfister <sophie@fluxlabs.ch>
 */
class ilObjOnlyOffice extends ilObjectPlugin
{

    use DICTrait;
    use OnlyOfficeTrait;

    const PLUGIN_CLASS_NAME = ilOnlyOfficePlugin::class;
    /**
     * @var ObjectSettings
     */
    protected $object_settings;

    /**
     * ilObjOnlyOffice constructor
     * @param int $a_ref_id
     */
    public function __construct(/*int*/ $a_ref_id = 0)
    {
        parent::__construct($a_ref_id);
    }

    /**
     * @inheritDoc
     */
    public final function initType()/*: void*/
    {
        $this->setType(ilOnlyOfficePlugin::PLUGIN_ID);
    }

    /**
     * @inheritDoc
     */
    public function doCreate()/*: void*/
    {
        $this->object_settings = new ObjectSettings();
        $this->object_settings->setObjId($this->id);
        $this->object_settings->setOpen($_POST[ilObjOnlyOfficeGUI::POST_VAR_OPEN_SETTING]);

        self::onlyOffice()->objectSettings()->storeObjectSettings($this->object_settings);
    }

    /**
     * @inheritDoc
     */
    public function doRead()/*: void*/
    {
        $this->object_settings = self::onlyOffice()->objectSettings()->getObjectSettingsById(intval($this->id));
    }

    /**
     * @inheritDoc
     */
    public function doUpdate()/*: void*/
    {
        self::onlyOffice()->objectSettings()->storeObjectSettings($this->object_settings);
    }

    /**
     * @inheritDoc
     */
    public function doDelete()/*: void*/
    {
        if ($this->object_settings !== null) {
            self::onlyOffice()->objectSettings()->deleteObjectSettings($this->object_settings);
        }
        $storage = new srag\Plugins\OnlyOffice\StorageService\StorageService(self::dic()->dic(),
            new ilDBFileVersionRepository(),
            new ilDBFileRepository(),
            new ilDBFileChangeRepository());
        $storage->deleteFile($this->getId());

    }

    /**
     * @inheritDoc
     * @param ilObjOnlyOffice $new_obj
     */
    protected function doCloneObject(/*ilObjOnlyOffice*/ $new_obj, /*int*/ $a_target_id, /*?int*/ $a_copy_id = null
    )/*: void*/
    {
        $new_obj->object_settings = self::onlyOffice()->objectSettings()->cloneObjectSettings($this->object_settings);
        $new_obj->object_settings->setObjId($new_obj->id);
        self::onlyOffice()->objectSettings()->storeObjectSettings($new_obj->object_settings);
        $storage = new srag\Plugins\OnlyOffice\StorageService\StorageService(self::dic()->dic(),
            new ilDBFileVersionRepository(),
            new ilDBFileRepository(),
            new ilDBFileChangeRepository());
        $storage->createClone($new_obj->getId(), $this->getId());
    }

    /**
     * @return bool
     */
    public function isOnline() : bool
    {
        return $this->object_settings->isOnline();
    }

    /**
     * @param bool $is_online
     */
    public function setOnline(bool $is_online = true)/*: void*/
    {
        $this->object_settings->setOnline($is_online);
    }

    public function setOpen(string $open = 'ilias') {
        $this->object_settings->setOpen($open);
    }

    public function getOpen(): string {
        return $this->object_settings->getOpen();
    }
}
