<?php
/**
 * @var $pdo
 */

session_start();
require_once "connection.php";
require "Category.php";
require "User.php";
require "Calendar.php";
require "Avatar.php";

$category = new Category($pdo);
$calendar = new Calendar($pdo);
$user = new User($pdo);
$avatar = new Avatar($pdo);

if ($_SESSION["role"] != "admin") {
    header("Location: dashboard.php");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["buttonAddCategory"])) {
        $err = NULL;
        if (empty(trim($_POST["categoryName"]))) {
            $err .= "Please enter category name first.<br>";
        } else {
            $categoryName = trim($_POST["categoryName"]);
        }

        if (empty($err)) {
            $category->insertCategory($categoryName);
        }
    }
}

if (isset($_GET["deleteCategory"])) {
    $category->deleteCategory($_GET["deleteCategory"]);
    header("Location: admin.php");
}

if(isset($_GET["deleteCalendar"])) {
    $calendar->deleteCalendar($_GET["deleteCalendar"]);
    header("Location: admin.php");
}

if(isset($_GET["deleteUser"])) {
    $calendarIds = $calendar->getCalendarsByUserId($_GET["deleteUser"]);
    if(!empty($calendarIds[0]["id"])) {
        foreach($calendarIds as $item) {
            $calendar->deleteCalendar($item["id"]);
        }
    }

    $u = $user->getUser($_GET["deleteUser"]);

    if($u["AVATAR_id_avatar"] != 1) {
        $path = $avatar->getPath($u["AVATAR_id_avatar"]);
        $path = "img/" . $path;
        unlink($path);
        $avatar->delete($avatar->getId($u["AVATAR_id_avatar"]));
    }

    $user->deleteUser($_GET["deleteUser"]);
    header("Location: admin.php");
}

?>

<!doctype html>

<html lang="en">
<head>
    <meta charset="utf-8">

    <title>Admin</title>
    <meta name="description" content="Free calendar app">
    <meta name="author" content="Vojtěch Hudec">

    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
<header>
    <div class="logo">
        <h1><a href="index.php">Calendar</a></h1>
    </div>
    <nav>
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="logout.php">Log out</a></li>
        </ul>
    </nav>
</header>
<div class="container">
    <div class="categories">
        <table>
            <tr>
                <th>Category name</th>
                <th>Action</th>
            </tr>
            <?php
            $categories = $category->getCategories();
            foreach ($categories as $item) {
                echo '<tr><td>' . $item["name"] . '</td><td>' . '<a href="admin.php?deleteCategory=' . $item["id_category"] . '">Delete</a>' . '</td></tr>';
            }
            ?>
        </table>
        <form action="admin.php" method="POST" name="addCategory">
            <label for="categoryName">Category name:</label>
            <input type="text" name="categoryName">
            <input type="submit" value="Add category" name="buttonAddCategory">
        </form>
    </div>

    <div class="users">
        <table>
            <tr>
                <th>Email</th>
                <th>Firstname</th>
                <th>Lastname</th>
                <th>Role</th>
                <th>Action</th>
            </tr>
            <?php
            $users = $user->getUsers();
            foreach ($users as $item) {
                echo '<tr><td>' . $item["email"] . '</td><td>' . $item["firstname"] . '</td><td>' . $item["lastname"] .
                    '</td><td>' . $item["role"] . '</td><td><a href="settings.php?id=' . $item["id_user"] . '">Edit</a>
 <a href="admin.php?deleteUser=' . $item["id_user"] . '">Delete</a></td></tr>';
            }
            ?>
        </table>
    </div>

    <div class="calendars">
        <table>
            <tr>
                <th>Name</th>
                <th>Expiration</th>
                <th>Action</th>
            </tr>
            <?php
            $calendars = $calendar->getCalendars();
            foreach ($calendars as $item) {
                $expiration = ($item["valid_until"] == "0000-00-00") ? "never" : $item["valid_until"];
                echo '<tr><td>' . $item["name"] . '</td><td>' . $expiration . '</td><td>' .
                    '</td><td><a href="admin.php?deleteCalendar=' . $item["id_calendar"] . '">Delete</a></td></tr>';
            }
            ?>
        </table>
    </div>
</div>
<footer>
    <p>2020 Vojtěch Hudec</p>
</footer>
</body>
</html>
