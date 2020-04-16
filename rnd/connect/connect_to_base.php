<?php
$dbUsername='dminfo_cleanelly';
$dbPassword='-jy8xqSz';

// $dbUsername='root';
// $dbPassword='';
$ConnectString='mysql:host=localhost;dbname=ddminfo_cleanelly_poll;charset=utf8';
$db = new PDO($ConnectString, $dbUsername, $dbPassword);
?>