<?php

if (isset($_POST['logout-submit'])) {
    session_start();
    session_unset();
    session_destroy();
    header('location: access/login.php');
}
