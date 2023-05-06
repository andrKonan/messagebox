<?php
require "util.php";
setcookie("token", "", time()-3600);
setcookie("username", "", time()-3600);
header('Location: index.php');
?>