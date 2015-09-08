<?php
function postComment($url, $comment) {
    global $GITHUB_TOKEN;
    $data = json_encode(array("body" => $comment));
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL,            str_replace('pulls', 'issues', $url) . "/comments?access_token=" . $GITHUB_TOKEN);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST,           1);
    curl_setopt($ch, CURLOPT_POSTFIELDS,     $data);
    curl_setopt($ch, CURLOPT_HTTPHEADER,     array('Content-Type: application/json'));
    $agent = 'KnightHacksBot';
    curl_setopt($ch, CURLOPT_USERAGENT, $agent);

    $result = curl_exec($ch);
}
