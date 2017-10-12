<?php
    ob_start();
    session_start();

    $timezone = date_default_timezone_set("America/Los_Angeles");

    $con = mysqli_connect(ini_get("mysqli.default_host"), ini_get("mysqli.default_user"), ini_get("mysqli.default_pw"), "slotify");

    if (mysqli_connect_errno()) {
        echo "Failed to connect: " . mysqli_connect_errno();
    }
?>
