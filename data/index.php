<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Новая страница");
?>

<div class="link-form">
    <?
        if(isset($_COOKIE["send"])){ 
            echo '<h3>Ваша ссылка: '.$_COOKIE["send"]."</h3>"; 
            $request = [
                "link" => "",
                "name" => "",
                "time" => "",
            ];
        }

        elseif(isset($_COOKIE["errors"])){ 
            echo $_COOKIE["errors"];
            $request = json_decode($_COOKIE["request"], 1);
        }
    ?>
    <form action="<?=SITE_TEMPLATE_PATH?>/ajax.php" method="post" style="display:flex; flex-direction:column;">
        <input name="link" type="text" placeholder="Ваша ссылка" style="margin-bottom:10px;" value = "<?=$request["link"]?>">
        <input name="name" type="text" placeholder="Слово, на которое будет заменена ссылка" style="margin-bottom:10px;" value = "<?=$request["name"]?>">
        <input name="time" type="text" placeholder="Время жизни ссылки в секундах (необязательно)" value = "<?=$request["time"]?>">
        <input class="btn" type="submit" value="Отправить" style="width: 120px; margin-top:15px;">
    </form>
</div>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>