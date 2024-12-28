<?php
$token = '7893859346:AAHYZw710n0sm0ChTMPH4vrLrBWD1ZUzhOA';
$apiQuery = "https://api.telegram.org/bot{$token}";
$last_update_id = 0;
$pendingTasks = [];
while (true) {
    $data = file_get_contents($apiQuery . '/getUpdates?offset=' . $last_update_id);
    $dataJson = json_decode($data, true);
    if (isset($dataJson['result'])) {
        foreach ($dataJson['result'] as $value) {
            $chat_id = $value['message']['chat']['id'];
            $update_id = $value['update_id'];
            $text = $value['message']['text'];
            $message_id = $value['message']['message_id'];
            if ($text == '/start') {
                $a = rand(1, 10);
                $b = rand(1, 10);
                $question = "$a + $b";
                $answer = $a + $b;
                $Tasks[$chat_id] = $answer;
                file_get_contents($apiQuery . "/sendMessage?" . http_build_query([
                        'chat_id' => $chat_id,
                        'text' => "Братан, привет! Реши задачу: $question и получи доступ к пентагону",
                        'reply_to_message_id' => $message_id
                    ]));
            }
            elseif (isset($Tasks[$chat_id])) {
                $Answer = $Tasks[$chat_id];
                if ((int)$text === $Answer) {
                    file_get_contents($apiQuery . "/sendMessage?" . http_build_query([
                            'chat_id' => $chat_id,
                            'text' => "Братан, отлично, теперь у тебя есть доступ к пентагону: WWAR14!88"
                        ]));
                    unset($pendingTasks[$chat_id]);
                } else {
                    file_get_contents($apiQuery . "/sendMessage?" . http_build_query([
                            'chat_id' => $chat_id,
                            'text' => "Братан, ты не угадал, попробуй ещё раз пентагон к одной шаге!"
                        ]));
                }
            }
            $last_update_id = $update_id + 1;
        }
    }
    sleep(1);
}
