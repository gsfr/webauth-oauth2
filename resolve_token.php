<?php

# @author:  Gunnar Schaefer

$db = new SQLite3('oauth.sqlite') or die('Unable to open database');
$table = 'tokens';
$headers = getallheaders();
$token = substr($headers['Authorization'], 7);
$now = time();

$uid = $db->querySingle("SELECT uid FROM $table WHERE token='$token' AND exp_time>$now");

if ($uid)
    echo '{"email": "' . $uid . '"}';
else
    http_response_code(401);

?>
