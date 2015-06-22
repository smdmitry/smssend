<?php

include 'config.php';

$status = $argv[1];
$filename = $argv[2];

$filecontent = file_get_contents($filename);

$data = explode("\n\n", $filecontent);
$rawheaders = explode("\n", $data[0]);

$headers = array();
foreach ($rawheaders as $header) {
	$header = explode(':', $header);
	if (!empty($header[0])) {
		$title = $header[0];
		unset($header[0]);
		$headers[$title] = trim(implode(':', $header));
	}
}

$text = $data[1];
if (isset($headers['Alphabet'])) {
    if ($headers['Alphabet'] == 'UCS2') {
        $text = iconv('UCS-2BE', 'UTF-8', $text);
    } else if ($headers['Alphabet'] == 'ISO') {
        //$text = iconv('ISO', 'UTF-8', $text);
    } else if ($headers['Alphabet'] == 'Unicode') {
        //$text = iconv('ISO', 'UTF-8', $text);
    }
}

if ($status == 'RECEIVED' || $status == 'REPORT') {
    $from = $headers['From'];

    $postData = array(
        'status' => $status,
        'from' => $from,
        'text' => $text,
    );

    $hashstr = $status . $from . $text;
} else if ($status == 'SENT') {
    $to = $headers['To'];
    $messageId = isset($headers['Message_id']) ?: 0;

    $postData = array(
        'status' => $status,
        'to' => $to,
        'id' => $messageId,
        'text' => $text,
    );

    $hashstr = $status . $to . $messageId . $text;
}

$postData['hash'] = $hash = md5($config['salt'] . $hashstr);

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, 'https://smdmitry.com/autosms/index/receive/?type=' . $config['type']);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
$out = curl_exec($curl);
echo $out;
curl_close($curl);


