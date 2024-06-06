if (!CModule::IncludeModule("iblock"))
    die('Модуль "Инфоблоки" не найден!');

$rsElements = CIBlockElement::GetList(array("NAME" => "ASC"), array("IBLOCK_ID" => 57));

$rsElement = new CIBlockElement;

$iHeight = 200;
$iWidth = 200;

while ($arElement = $rsElements->Fetch()) {
    if ($arElement["DETAIL_PICTURE"] != "") {
        $arPreview = CFile::ResizeImageGet($arElement["DETAIL_PICTURE"], array('width' => $iWidth, 'height' => $iHeight), BX_RESIZE_IMAGE_PROPORTIONAL, false);
        $arLoadProductArray = Array(
            //"DETAIL_PICTURE"  => CFile::MakeFileArray(CFile::GetPath($arElement["DETAIL_PICTURE"])),
            "PREVIEW_PICTURE" => CFile::MakeFileArray($arPreview["src"]),
        );
        if ($rsElement->Update($arElement["ID"], $arLoadProductArray)) {
            echo "Элемент {$arElement["ID"]} обновлён.<br />\n";
        }
    } elseif ($arElement["PREVIEW_PICTURE"] != "") {
        $arOld = CFile::GetFileArray($arElement["PREVIEW_PICTURE"]);

        if (($arOld["WIDTH"] > $iWidth) || ($arOld["HEIGHT"] > $iHeight)) {
            $arLoadProductArray = Array(
                "DETAIL_PICTURE"  => CFile::MakeFileArray($arOld["SRC"]),
            );
            if ($rsElement->Update($arElement["ID"], $arLoadProductArray)) {
                $arNew = CFile::ResizeImageGet($arElement["PREVIEW_PICTURE"], array("width" => $iWidth, "height" => $iHeight), BX_RESIZE_IMAGE_PROPORTIONAL, false);
                $arLoadProductArray = Array(
                    "PREVIEW_PICTURE" => CFile::MakeFileArray($arNew["src"]),
                );
                if ($rsElement->Update($arElement["ID"], $arLoadProductArray)) {
                    echo "Элемент {$arElement["ID"]} обновлён.<br />\n";
                }
            }
        }
    }
}
