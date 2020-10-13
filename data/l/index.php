<?
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
?>

<?
    CModule::IncludeModule('iblock');
    $iblock_id = 1;
    $elem = CIBlockElement::GetList([], ["IBLOCK_ID" => $iblock_id, "ACTIVE" => "Y", "DETAIL_TEXT" => $_GET['l']
    array(
        "LOGIC" => "OR",
        array(">DATE_ACTIVE_TO" => date("d.m.Y H:i:s")),
        array("DATE_ACTIVE_TO" => false),
    )], 
    false, false, []);
    if($e = $elem->GetNext()){
        header('Location: ' . $e["NAME"]);
    } else{
        header('HTTP/1.0 404 Not Found');
        echo 'Unknown link.'; 
    }
?>