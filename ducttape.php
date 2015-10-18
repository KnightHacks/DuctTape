<?php
require_once("config.php");
require_once("github.php");
require_once("gifs.php");

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

$body = json_decode($body, true);
if ($body["pull_request"]["base"]["ref"] === "master") {
    if ($body['action'] === "opened") {
        postComment($body['pull_request']['url'], "**Notice**: This will go live on the website as soon as the pull request is closed.\n\nPlease ensure this has been tested properly on `master`.\n\nYou sho    uld not accept this PR by yourself. Someone else should accept it, preferably after reading it.");
        if ($config["lint_cmd"]) {
            updateCommitStatus($body['pull_request'], 'pending');

            $output = shell_exec($config["cmd"]);
        }
    }
    if ($body["action"] === "closed" && $body["pull_request"]["merged"] === true) {
        shell_exec($config["cmd"]);
        postComment($body['pull_request']['url'], "Deployed successfully. Please check your changes on " . $config['url'] . "\n<img src=\"" . $GIFS[array_rand($GIFS)] . "\">");
    }
}
