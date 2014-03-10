<?php

# @author:  Gunnar Schaefer

#$headers = getallheaders();
#error_log(print_r($_GET, true));

$table = 'tokens';
$now = time();
$token_ttl = 3600;
$token_expiration = $now + $token_ttl + 2;
$token = bin2hex(openssl_random_pseudo_bytes(32));
$uid = getenv('REMOTE_USER') . '@stanford.edu';
$client_id = $_GET['client_id'];
$url = $_GET['redirect_uri'];

$db = new SQLite3('oauth.sqlite') or die('Unable to open database');
$db->exec("CREATE TABLE IF NOT EXISTS $table (token STRING PRIMARY KEY, uid STRING, client_id STRING, exp_time INTEGER)");

# insert new token
$db->exec("INSERT INTO $table VALUES ('$token', '$uid', '$client_id', '$token_expiration')");

# delete any expired tokens
$db->exec("DELETE FROM $table WHERE exp_time<$now;");

$url .= '#access_token=' . $token;
$url .= '&state=' . $_GET['state'];
$url .= '&audience=' . $client_id;
$url .= '&expires_in=' . $token_ttl;

header('Location: ' . $url, true, 301);
die();

?>
