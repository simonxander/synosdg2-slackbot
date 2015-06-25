<?php

if (!isset($_POST['token']) || $_POST['token'] !== 'dDIfXgjFMCQDv8snkuPIP96i') {
    exit(0);
}
if (!isset($_POST['user_name']) || $_POST['user_name'] === 'slackbot') {
    exit(0);
}
if (!isset($_POST['user_id']) || $_POST['user_id'] === 'USLACKBOT') {
    exit(0);
}
if (!isset($_POST['text'])) {
    exit(0);
}

$data = array();

if (strpos($_POST['text'], "氣象") !== false || strpos($_POST['text'], "天氣") !== false) {
    $xml = simplexml_load_file("http://opendata.cwb.gov.tw/opendataapi?dataid=F-C0032-009&authorizationkey=CWB-E1DBB1C2-0A6A-4EB0-9BE6-358053080C85");
    if (FALSE === $xml) {
        exit(0);
    }
    $data["username"] = "台北市氣象";
    $data['text'] = "";
    $data['text'] .= $xml->dataset->location->locationName."\n";
    foreach ($xml->dataset->parameterSet->parameter as $param) {
        $data['text'] .= $param->parameterValue."\n";
    }
    goto End;
}

if (strpos($_POST['text'], "吃啥") !== false || strpos($_POST['text'], "吃什麼") !== false ||
    strpos($_POST['text'], "肚子餓") !== false) {
    $RestaurantArray = array("京讚", "京站", "珍珍水餃, 還可以買甜甜圈", "八方", "采岩堂", "日本人咖哩", "炒飯  (記得訂位 ^.<)", "新東陽", "新光三越", "永井壽司", "排骨酥", "摩斯", "王家漢堡");

    $data["username"] = "餐廳";
    $data["text"] = "吃".$RestaurantArray[rand()%count($RestaurantArray)];
}
if (strpos($_POST['text'], "飲料") !== false || strpos($_POST['text'], "喝什麼") !== false ||
    strpos($_POST['text'], "喝啥") !== false) {
    $DrinkArray = array("50嵐", "康青龍", "水巷茶弄", "星巴克", "冰箱茶裏王", "水");

    if (isset($data['username'])) {
        $data["username"] .= "、飲料";
    } else {
        $data["username"] = "飲料";
    }
    $data["text"] = (isset($data['text']) ? $data['text']."、" : "") . "喝".$DrinkArray[rand()%count($DrinkArray)];
}

End:
if (count($data) > 0) {
    $data["username"] .= "達人";
    echo json_encode($data);
}
exit(0);

?>
