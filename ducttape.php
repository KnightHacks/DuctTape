<?php
require_once("config.php");

$config = null;
foreach($REPOS as $repo => $repo_config) {
    if ($repo === $_GET['repo']) {
        $config = $repo_config;
        break;
    }
}
$body = file_get_contents("php://input");
if (!$config || "sha1=" . hash_hmac("sha1", $body, $config["secret"]) !== $_SERVER['HTTP_X_HUB_SIGNATURE']) {
    die("404?");
}

$body = json_decode($body);
if ($body["pull_request"]["base"]["ref"] === "production") {
    if ($body["action"] === "closed" && $body["pull_request"]["merged"] === true) {
        system($repo_config["cmd"]);
    }
}
