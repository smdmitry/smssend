<?php

include 'config.php';

$phone = $argv[1];
$text = $argv[2];
$text = $text[0] == '\'' ? substr($text, 1) : $text;

$commandId = (int)substr($text, 0, 2);

sleep(1);
//sleep(rand(1, 20));

$status = 'RECEIVED';
$response = 'Неверный код';

if ($commandId == 1) {
    $response = 'Баланс лицевого счета, руб.: 408';
} elseif ($commandId == 2) {
    $response = 'Режим охраны выключен
ОП13,3В
РП 0,0В
+32°C
v 3. 2.13';
} elseif ($commandId == 3) {
    $response = $config['coords'];
} elseif ($commandId == 5 || $commandId == 6 || $commandId == 7 || $commandId == 12) {
    $response = 'Команда выполнена';
} elseif ($commandId == 11) {
    $response = 'Двигатель заведен';
}

if (!empty($response)) {
    $phone = str_replace('+', '', $phone);

    $text = $response;
    $from = $phone;

    $postData = array(
        'status' => $status,
        'from' => $from,
        'text' => $text,
    );
    $hashstr = $status . $from . $text;
    $postData['hash'] = $hash = md5($config['salt'] . $hashstr);

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, 'http://smdmitry.com/autosms/index/receive/?type=' . $config['type']);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
    $out = curl_exec($curl);

    var_dump($out);

    curl_close($curl);
}