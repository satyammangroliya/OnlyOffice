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

if (($body_stream = file_get_contents("php://input")) === false) {
    echo "Bad Request";
}

$DIC->logger()->root()->info($body_stream);
$data = json_decode($body_stream, true);

if ($data["status"] == 2) {
    $uuid = $_GET['uuid'];
    $file_id = $_GET['file_id'];
    $file_ext = $_GET['ext'];

    $downloadUri = $data["url"];
    $changes_object = (json_encode($data["history"]["changes"]));
    $changes_object = str_replace('[{', '{', $changes_object);
    $changes_object = str_replace('}]', '}', $changes_object);

    $DIC->logger()->root()->info("Changes: " . $changes_object);
    $editor = $data["users"][0];
    $DIC->logger()->root()->info("Editor: " . $editor);
    $serverVersion = $data["history"]["serverVersion"];
    $DIC->logger()->root()->info("Server: " . $serverVersion);
    $OO_changesurl = $data["changesurl"];
    $DIC->logger()->root()->info("ChangesUrl: " . $OO_changesurl);
    $change_ext = pathinfo($OO_changesurl, PATHINFO_EXTENSION);
    $DIC->logger()->root()->info("Extension: " . $change_ext);

    if (($new_data = file_get_contents($downloadUri)) === false  ||
        ($new_change = file_get_contents($OO_changesurl)) === false) {
        echo "Bad Response";

    } else {
        $callback_handler = new xonoCallbackHandler($DIC, $new_data, $uuid, $file_id, $editor, $file_ext, $changes_object, $serverVersion, $new_change, $change_ext);
        $callback_handler->handleCallback();
       /*
        string $changes_object,
        string $serverVersion,
        string $change_content,
        string $change_extension */
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