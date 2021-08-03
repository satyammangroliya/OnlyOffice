<?php
// Try to determine ILIAS-root
$directory = strstr($_SERVER['SCRIPT_FILENAME'], 'Customizing', true);
if (is_file('path.txt')) {
    $directory = trim(file_get_contents('path.txt'));
}

chdir($directory);

require_once 'Customizing/global/plugins/Services/Repository/RepositoryObject/OnlyOffice/classes/Init/xonoInitialisation.php';
xonoInitialisation::init();
global $DIC;
echo get_class($DIC->database());
// use database
