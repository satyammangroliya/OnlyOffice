<#1>
<?php
\srag\Plugins\OnlyOffice\Repository::getInstance()->installTables();
?>
<#2>
<?php
\srag\Plugins\OnlyOffice\StorageService\Infrastructure\File\FileAR::updateDB();
\srag\Plugins\OnlyOffice\StorageService\Infrastructure\File\FileVersionAR::updateDB();
\srag\Plugins\OnlyOffice\StorageService\Infrastructure\File\FileVersionAR::updateDB();
\srag\Plugins\OnlyOffice\StorageService\Infrastructure\File\FileVersionAR::updateDB();
\srag\Plugins\OnlyOffice\StorageService\Infrastructure\File\FileVersionAR::updateDB();
?>
<#3>
<?php
\srag\Plugins\OnlyOffice\StorageService\Infrastructure\File\FileChangeAR::updateDB();
?>
<#4>
<?php
\srag\Plugins\OnlyOffice\StorageService\Infrastructure\File\FileAR::updateDB();
\srag\Plugins\OnlyOffice\ObjectSettings\ObjectSettings::updateDB();
\srag\Plugins\OnlyOffice\StorageService\Infrastructure\File\FileAR::updateDB();
?>
<#5>
<#6>
<#7>
<?php
require_once("./Services/Migration/DBUpdate_3560/classes/class.ilDBUpdateNewObjectType.php");
$xono_type_id = ilDBUpdateNewObjectType::addNewType(ilOnlyOfficePlugin::PLUGIN_ID, 'Plugin OnlyOffice');

//Adding a new Permission rep_robj_xono_editFile ("editFile")
$offering_admin = ilDBUpdateNewObjectType::addCustomRBACOperation( //$a_id, $a_title, $a_class, $a_pos
    'rep_robj_xono_perm_editFile', 'editFile', 'object', 2010);
if ($offering_admin) {
    ilDBUpdateNewObjectType::addRBACOperation($xono_type_id, $offering_admin);
}
?>
<#8>
<?php
\srag\Plugins\OnlyOffice\StorageService\Infrastructure\File\FileAR::updateDB();
?>
<#9>
<?php
\srag\Plugins\OnlyOffice\ObjectSettings\ObjectSettings::updateDB();
?>
<#10>
<?php
\srag\Plugins\OnlyOffice\ObjectSettings\ObjectSettings::updateDB();
?>
<#11>
<?php
\srag\Plugins\OnlyOffice\ObjectSettings\ObjectSettings::updateDB();
?>
<#12>
<#13>
<?php
\srag\Plugins\OnlyOffice\StorageService\Infrastructure\File\FileChangeAR::updateDB();
global $DIC;
$DIC->database()->query("CREATE TABLE IF NOT EXISTS xono_file_change_seq (sequence INT PRIMARY KEY AUTO_INCREMENT);");
$DIC->database()->query("INSERT INTO xono_file_change_seq VALUES (1);");
?>

