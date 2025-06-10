<?php
session_start();
session_destroy();
header('Location: /burgeez/index.php'); // or '/' if your homepage is at the root
exit;
?>