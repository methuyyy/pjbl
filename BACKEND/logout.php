<?php
session_start();
session_unset();
session_destroy();
header("Location: ../VIEWS/USER/loginbaru.php");
exit;
?>
