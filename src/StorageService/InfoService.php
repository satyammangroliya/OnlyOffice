<?php

namespace srag\Plugins\OnlyOffice\StorageService;

use srag\Plugins\OnlyOffice\StorageService\Infrastructure\File\ilDBFileRepository;
use srag\Plugins\OnlyOffice\StorageService\Infrastructure\File\ilDBFileChangeRepository;
use srag\Plugins\OnlyOffice\StorageService\Infrastructure\File\ilDBFileVersionRepository;
use srag\Plugins\OnlyOffice\StorageService\Infrastructure\Common\UUID;
use srag\Plugins\OnlyOffice\Utils\OnlyOfficeTrait;
use ILIAS\DI\Container;

class InfoService
{
    use OnlyOfficeTrait;

    /**
     * @var Container
     */
    protected $dic;
    /**
     * @var FileVersionRepository
     */
    protected $file_version_repository;
    /**
     * @var FileRepository
     */
    protected $file_repository;
    /** @var FileChangeRepository */
    protected $file_change_repository;

    public function __construct($dic)
    {
        $this->dic = $dic;
        $this->file_repository = new ilDBFileRepository();
        $this->file_change_repository = new ilDBFileChangeRepository();
        $this->file_version_repository = new ilDBFileVersionRepository();
    }

    public function getUuid(int $file_id): UUID {
        return $this->file_repository->getFile($file_id)->getFileUuid();
    }

    public function getOpenSetting(int $file_id): string {
        return $this->file_repository->getFile($file_id)->getOpenSetting();
    }

    public static final function getBaseUrl(): string{
        return self::onlyOffice()->config()->getValue("baseurl");
    }

    public static final function getOnlyOfficeUrl(): string {
        return self::onlyOffice()->config()->getValue("onlyoffice_url");
    }

    public static final function getSecret(): string {
        return self::onlyOffice()->config()->getValue("onlyoffice_secret");
    }

}