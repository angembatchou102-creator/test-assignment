<?php
session_destroy();
session_unset();
session_destroy();
header("location:index.php");
exit();
?>