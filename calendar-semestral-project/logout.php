<?php
session_start();
unset($_SESSION["id"]);
unset($_SESSION["role"]);
unset($_SESSION["email"]);
unset($_SESSION["firstname"]);
unset($_SESSION["lastname"]);
unset($_SESSION["avatar"]);
unset($_SESSION["calendarId"]);
header("location: login.php");