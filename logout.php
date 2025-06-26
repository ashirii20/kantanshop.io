<?php
session_start();
session_destroy();
header('Location: loginsignup.html');
exit;
?>