<?php
/*
 * This file is used to log out the user. It destroys the session and redirects the user to the index page.
 */
session_start();
$_SESSION['user_id'] = null;
$_SESSION['role'] = null;
session_destroy();
header("Location:  ../index.php");
exit();