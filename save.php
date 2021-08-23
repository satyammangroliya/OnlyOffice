<?php
// Try to determine ILIAS-root
$directory = strstr($_SERVER['SCRIPT_FILENAME'], 'Customizing', true);
if (is_file('path.txt')) {
    $directory = trim(file_get_contents('path.txt'));
}

chdir($directory);
//echo get_class($DIC->database());
// use database

initializeILIAS();
global $DIC;
//$DIC->logger()->root()->info("Ilias initialized");

if (($body_stream = file_get_contents("php://input")) === false) {
    echo "Bad Request";
}

//$DIC->logger()->root()->info($body_stream);
$encrypted = json_decode($body_stream, true);
require_once 'Customizing/global/plugins/Services/Repository/RepositoryObject/OnlyOffice/src/CryptoService/JwtService.php';
$decrypted = \srag\Plugins\OnlyOffice\CryptoService\JwtService::jwtDecode($encrypted['token'],
    "secret"); //ToDo: Set password globally
//$DIC->logger()->root()->info($decrypted);
$data = json_decode($decrypted, true);

if ($data["status"] == 2 && !$data["notmodified"] || $data["status"] == 6) {
    $DIC->logger()->root()->info("Save File");
    $uuid = $_GET['uuid'];
    $file_id = $_GET['file_id'];
    $file_ext = $_GET['ext'];

    try {
        $callback_handler = new xonoCallbackHandler($DIC, $uuid, $file_id, $data);
        $callback_handler->handleCallback();
    } catch (Exception $e) {
        echo $e->getMessage();
    }

}
echo "{\"error\":0}";
exit;

//-------------------------------------------------------------------

function initializeILIAS()
{
    $session_jwt = $_GET['token'];
    require_once 'Customizing/global/plugins/Services/Repository/RepositoryObject/OnlyOffice/src/CryptoService/JwtService.php';
    $session = \srag\Plugins\OnlyOffice\CryptoService\JwtService::jwtDecode($session_jwt,
        "secret"); // ToDo: Define Key globally
    $session_array = json_decode(json_decode($session, true), true);
    $session_id = $session_array['session_id'];
    //$client_id = $session_array['client_id'];
    $file_id = $_GET['file_id'];
    $uuid = $_GET['uuid'];

    $_COOKIE['PHPSESSID'] = $session_id;
    $_COOKIE['ilClientId'] = 'default';
    ilContext::init(ilContext::CONTEXT_SOAP_NO_AUTH);
    require_once("Services/Init/classes/class.ilInitialisation.php");
    ilInitialisation::initILIAS();
}