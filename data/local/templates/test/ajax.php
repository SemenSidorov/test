<?
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
?>

<?
function new_hash(){

    $length = rand(5, 11);

    return substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'), 1, $length);

}

if (empty($_REQUEST['link'])) { 

    $value = '<h5 style="color: red;">Не заполнено обязательное поле: "Ваша ссылка"</h5>';
    setcookie("errors", $value, time()+10, "/");
    setcookie("request", json_encode($_REQUEST), time()+10, "/");
    setcookie("send", "", time());
    header("Location: ".$_SERVER["HTTP_REFERER"]);
    die;

} elseif(!empty($_REQUEST['time']) && $_REQUEST['time'] !== preg_replace("/[^0-9]/", '', $_REQUEST['time'])) {

    $value = '<h5 style="color: red;">Не корректно заполнено поле: "Время жизни ссылки в секундах"</h5>';
    setcookie("errors", $value, time()+10, "/");
    setcookie("request", json_encode($_REQUEST), time()+10, "/");
    setcookie("send", "", time());
    header("Location: ".$_SERVER["HTTP_REFERER"]);
    die;

} else {
    
    $siteUrl = ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['SERVER_NAME'];

    $hash = new_hash();

    CModule::IncludeModule('iblock');

    $el = new CIBlockElement;
    $iblock_id = 1;

    if(isset($_POST['time']) && !empty($_POST['time'])){ 
        $fields["ACTIVE_TO"] = date("d.m.Y H:i:s", time() + $_POST['time']); 
    }

    $filter_name = CIBlockElement::GetList([], ["IBLOCK_ID" => $iblock_id, 
    array(
        "LOGIC" => "OR",
        array(">DATE_ACTIVE_TO" => date("d.m.Y H:i:s")),
        array("DATE_ACTIVE_TO" => false),
    ), "ACTIVE" => "Y", "NAME" => strip_tags($_REQUEST['link'])], false, false, []);

    $check_hash = true;

    while($check_hash){

        $filter_text = CIBlockElement::GetList([], ["IBLOCK_ID" => $iblock_id, 
        array(
            "LOGIC" => "OR",
            array(">DATE_ACTIVE_TO" => date("d.m.Y H:i:s")),
            array("DATE_ACTIVE_TO" => false),
        ), "ACTIVE" => "Y", "DETAIL_TEXT" => $hash], false, false, []);

        if($e = $filter_text->GetNext()){ $hash = new_hash(); } else { $check_hash = false; }

    }

    $fields = array(
        "DATE_CREATE" => date("d.m.Y H:i:s"),
        "CREATED_BY" => $GLOBALS['USER']->GetID(),
        "IBLOCK_ID" => $iblock_id,
        "NAME" => strip_tags($_REQUEST['link']),
        "DETAIL_TEXT" => $hash,
        "ACTIVE" => "Y",
    );

    if($e = $filter_name->GetNext()){
        $value = $siteUrl.'/l/'.$e["DETAIL_TEXT"];
        setcookie("send", $value, time()+10, "/");
        setcookie("errors", "", time());
        setcookie("request", "", time());
        header("Location: ".$_SERVER["HTTP_REFERER"]);
        die;
    }

    elseif(strpos(strip_tags($_REQUEST['link']), $siteUrl) === false){
        $value = '<h5 style="color: red;">Неверно введён домен сайта (Должно быть: '.$siteUrl.'/)</h5>';
        setcookie("errors", $value, time()+10, "/");
        setcookie("request", json_encode($_REQUEST), time()+10, "/");
        setcookie("send", "", time());
        header("Location: ".$_SERVER["HTTP_REFERER"]);
        die;
    }
    
    elseif ($ID = $el->Add($fields)) {
        $value = $siteUrl.'/l/'.$hash;
        setcookie("send", $value, time()+10, "/");
        setcookie("errors", "", time());
        setcookie("request", "", time());
        header("Location: ".$_SERVER["HTTP_REFERER"]);
        die;

    } else {
        $value = '<h5 style="color: red;">Произошел как-то косяк Попробуйте еще разок</h5>';
        setcookie("errors", $value, time()+10, "/");
        setcookie("request", json_encode($_REQUEST), time()+10, "/");
        setcookie("send", "", time());
        header("Location: ".$_SERVER["HTTP_REFERER"]);
        die;
    }
}
?>