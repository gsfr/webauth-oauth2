<?php

# @author:  Gunnar Schaefer

$db = new SQLite3('oauth.sqlite') or die('Unable to open database');
$table = 'tokens';
$token = $_GET['access_token'];
$now = time();

$client_id = $db->querySingle("SELECT client_id FROM $table WHERE token='$token' AND exp_time>$now");

header('Access-Control-Allow-Origin: *');
if ($client_id)
    echo '{"audience": "' . $client_id . '"}';
else
    http_response_code(401);

?>
