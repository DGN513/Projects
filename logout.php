<?php
require "connect.php";
setcookie('idUser', $idUser, time() - 3600, "/");
header("Location: index.php");
exit();
?>