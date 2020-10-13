<?
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
?>

<?
    CModule::IncludeModule('iblock');
    $iblock_id = 1;
    $elem = CIBlockElement::GetList([], ["IBLOCK_ID" => $iblock_id, "ACTIVE" => "Y", 
    array(
        "LOGIC" => "OR",
        array(">DATE_ACTIVE_TO" => date("d.m.Y H:i:s")),
        array("DATE_ACTIVE_TO" => false),
    )], 
    false, false, []);
    $links = [];
    while($e = $elem->GetNext()){
        $links[$e["DETAIL_TEXT"]] = $e["NAME"];
    } 
    if(isset($_GET['l']) && array_key_exists($_GET['l'], $links)){
        header('Location: ' . $links[$_GET['l']]);
    } else{
        header('HTTP/1.0 404 Not Found');
        echo 'Unknown link.'; 
    }
?>