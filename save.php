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
$DIC->logger()->root()->info("Ilias initialized");


if (($body_stream = file_get_contents("php://input"))===FALSE){
    echo "Bad Request";
}

$DIC->logger()->root()->info($body_stream);
$data = json_decode($body_stream, TRUE);

if ($data["status"] == 2){
    $uuid = $_GET['uuid'];
    $file_id = $_GET['file_id'];
    $ext = $_GET['ext'];




    $downloadUri = $data["url"];
    $editor = $data["users"][0];

    if (($new_data = file_get_contents($downloadUri))===FALSE){
        echo "Bad Response";

    } else {
        $callback_handler = new xonoCallbackHandler($DIC, $new_data, $uuid, $file_id, $editor, $ext);
        $callback_handler->handleCallback();
        //$DIC->ctrl()->redirect($callback_handler, xonoCallbackHandlerGUI::CMD_HANDLE_CALLBACK);
        //$DIC->ctrl()->forwardCommand($callback_handler);
    }
}
echo "{\"error\":0}";
exit;

//-------------------------------------------------------------------



function initializeILIAS() {
    $session_jwt = $_GET['token'];
    require_once 'Customizing/global/plugins/Services/Repository/RepositoryObject/OnlyOffice/src/CryptoService/JwtService.php';
    $session = \srag\Plugins\OnlyOffice\CryptoService\JwtService::jwtDecode($session_jwt, "secret"); // ToDo: Define Key globally
    $session_array = json_decode(json_decode($session, TRUE), TRUE);
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