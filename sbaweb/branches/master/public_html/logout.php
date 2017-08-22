<?php
include_once "common.php";
session_destroy();
sendRedirect('login.php');
?>