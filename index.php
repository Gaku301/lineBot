<?php

// チャンネルアクセストークンを入力
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
    $return_message_sticker_packageId = '11538';
    $return_message_sticker_stickerId = '51626496';
}

//返信メッセージ
if (preg_match('/だれ|誰/', $message_text)) {
    $return_message_text = 'こっちのセリフだよwww';
} elseif (preg_match('/よろしく/', $message_text)) {
    $return_message_text = 'お、おうw　よろしくな！w';
} elseif (preg_match('/やるじゃん|すごい/', $message_text)) {
    $return_message_text = 'まぁなwww';
} elseif (preg_match('/言えない|言えん|いえん|いえない/', $message_text)) {
    $return_message_text = 'ばーかww　なめんなよww';
} else {
    $return_message_text = '「'.$message_text.'」じゃねーよｗｗｗ';
}

//返信実行
sending_messages($accessToken, $replyToken, $message_type, $return_message_text, $return_message_sticker_packageId, $return_message_sticker_stickerId);
?>
<?php
//メッセージの送信
function sending_messages($accessToken, $replyToken, $message_type, $return_message_text, $return_message_sticker_packageId, $return_message_sticker_stickerId)
{
    //レスポンスフォーマット
    $response_format_text = [
        'type' => $message_type,
        'text' => $return_message_text,
    ];
    $response_format_sticker = [
        'type' => 'sticker',
        'packageId' => $return_message_sticker_packageId,
        'stickerId' => $return_message_sticker_stickerId,
    ];

    //ポストデータ
    $post_data = [
        'replyToken' => $replyToken,
        'messages' => [$response_format_text, $response_format_sticker],
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