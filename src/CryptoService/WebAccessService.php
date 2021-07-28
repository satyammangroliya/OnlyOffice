<?php

namespace srag\Plugins\OnlyOffice\CryptoService;

use ilWACSignedPath;
use ilWebAccessChecker;
use ilUtil;
use ilFileDelivery;

class WebAccessService
{

    public static function getWACUrl(string $url) : string
    {
        ilWACSignedPath::setTokenMaxLifetimeInSeconds(ilWACSignedPath::MAX_LIFETIME);
        $file_path = ilWACSignedPath::signFile(ilUtil::getWebspaceDir() . $url);
        $file_path .= '&' . ilWebAccessChecker::DISPOSITION . '=' . ilFileDelivery::DISP_ATTACHMENT;
        return $file_path;

    }

}