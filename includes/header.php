<?php
include("config.php");

if (isset($_SESSION['userLoggedIn'])) {
    $userLoggedIn = $_SESSION['userLoggedIn'];
} else {
    header("Location: register.php");
}
?>

<html>
<head>
    <title>Slotify</title>
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
</head>
<body>
<div id="mainContainer">
    <div id="topContainer">
        <?php include("navBarContainer.php"); ?>
        <div id="mainViewContainer">
            <div id="mainContent">