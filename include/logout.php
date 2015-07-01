<?php
require_once '/opt/lampp/htdocs/MySpace/src/init.php';

$user=new user();
$user->logout();
redirect::to('login.php')
?>