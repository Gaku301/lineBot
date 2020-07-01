<?php

// require_once __DIR__.'/vendor/autoload.php';

// $httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient(getenv('CHANNEL_ACCESS_TOKEN'));
// $bot = new \LINE\LINEBot($httpClient, ['channelSecret' => getenv('CHANNEL_SECRET')]);
// $sign = $_SERVER['HTTP_'.\LINE\LINEBot\Constant\HTTPHeader::LINE_SIGNATURE];
// $events = $bot->parseEventRequest(file_get_contents('php://input'), $sign);

// foreach ($events as $event) {
//     if (!($event instanceof \LINE\LINEBot\Event\MessageEvent) ||
//         !($event instanceof \LINE\LINEBot\Event\MessageEvent\TextMessage)) {
//         continue;
//     }
//     $bot->replyText($event->getReplyToken(), $event->getText());
// }

$accessToken = 'H9W/gAYIJIXGoTFy7OiusrdhT4/F23bZ4oE5HcIzOTc4c4CXSnHYxLUNPigwHAywndFLoNwZCoa0DU5sNq2yfqwCsS0Ps9abSYsgIVgNo+ygvnA1IJgkXz2WLjfjTc1Q4uG9KfwPX/HBko2YGGnE/AdB04t89/1O/w1cDnyilFU=';

//ユーザーからのメッセージ取得
$json_string = file_get_contents('php://input');
$json_object = json_decode($json_string);

//取得データ
$replyToken = $json_object->{'events'}[0]->{'replyToken'};        //返信用トークン
$message_type = $json_object->{'events'}[0]->{'message'}->{'type'};    //メッセージタイプ
$message_text = $json_object->{'events'}[0]->{'message'}->{'text'};    //メッセージ内容

//メッセージタイプが「text」以外のときは何も返さず終了
if ($message_type != 'text') {
    exit;
}

if (preg_match('/だれ/', $message_text)) {
    $return_message_text = 'こっちのセリフだよwww';
} elseif ($message_text === 'やるじゃん') {
    $return_message_text = 'まぁなwww';
} else {
    $return_message_text = '「'.$message_text.'」じゃねーよｗｗｗ';
}

//返信メッセージ
// $return_message_text = '「'.$message_text.'」じゃねーよｗｗｗ';

//返信実行
sending_messages($accessToken, $replyToken, $message_type, $return_message_text);
?>
<?php
//メッセージの送信
function sending_messages($accessToken, $replyToken, $message_type, $return_message_text)
{
    //レスポンスフォーマット
    $response_format_text = [
        'type' => $message_type,
        'text' => $return_message_text,
    ];

    //ポストデータ
    $post_data = [
        'replyToken' => $replyToken,
        'messages' => [$response_format_text],
    ];

    //curl実行
    $ch = curl_init('https://api.line.me/v2/bot/message/reply');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json; charser=UTF-8',
        'Authorization: Bearer '.$accessToken,
    ));
    $result = curl_exec($ch);
    curl_close($ch);
}
?>