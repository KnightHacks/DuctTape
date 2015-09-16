<?php
function ghApi($url, $data) {
    global $GITHUB_TOKEN;
    $data = json_encode($data);
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url . "?access_token=" . $GITHUB_TOKEN);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST,           1);
    curl_setopt($ch, CURLOPT_POSTFIELDS,     $data);
    curl_setopt($ch, CURLOPT_HTTPHEADER,     array('Content-Type: application/json'));
    $agent = 'KnightHacksBot';
    curl_setopt($ch, CURLOPT_USERAGENT, $agent);
    return curl_exec($sh);
}
function postComment($url, $comment) {
    ghApi(str_replace('pulls', 'issues', $url) . "/comments", array("body" => $comment));
}
function updateCommitStatus($pull_request, $status, $message) {
    $sha = $pull_request["body"]["after"];
    if (!$sha) {
        $sha = $pull_request["body"]["pull_request"]["head"]["sha"];
    }
    $url = "/repos/" . $pull_request["body"]["pull_request"]["head"]["repo"]["full_name"] . "/statuses/" . $sha;
    ghApi(str_replace('pulls', 'issues', $url) . "/comments", array("state" => $status, "description" => $message));
}
