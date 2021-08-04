<?php

require_once 'libs/composer/vendor/autoload.php';
require_once "include/inc.ilias_version.php";
use ILIAS\DI\Container;
/**
 * @author Theodor Truffer <tt@studer-raimann.ch>
 */
class xonoInitialisation extends ilInitialisation
{
    public static function init()
    {
        global $DIC;
        $DIC = new Container();
        self::initIliasIniFile();
        self::determineClient();
        self::initClientIniFile();
        self::initDatabase();
        self::initLog();
    }
}