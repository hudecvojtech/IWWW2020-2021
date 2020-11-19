<?php
/**
 * @var $pdo
 */

session_start();
require "connection.php";
require "User.php";

if(isset($_GET["id"])) {
    $_SESSION["calendarId"] = $_GET["id"];
} else {
    $_SESSION["calendarId"] = 0;
}

if(isset($_GET["deleteEvent"])) {
    $eventId = $_GET["deleteEvent"];
    User::deleteEvent($eventId, $pdo);
}

if(isset($_GET["deleteCalendar"])) {
    $calendarId = $_GET["deleteCalendar"];
    User::deleteCalendar($calendarId, $pdo);
}

if(isset($_POST["addCalendarButton"])) {
    $err = NULL;

    if(empty($_POST["calendarname"])) {
        $err .= "Name is not set.";
    } else {
        $name = $_POST["calendarname"];
    }

    if(!empty($_POST["expiration"])) {
        if (empty($_POST["validUntil"])) {
            $err .= "Valid until is not set.";
        } else {
            $validUntil = $_POST["validUntil"];
        }
    } else {
        $validUntil = NULL;
    }

    if(empty($err)) {
        User::calendarInsert($name, $validUntil, $pdo);
    }

    echo $err;

}

if(isset($_POST["addEventButton"])) {
    $err = NULL;

    if(empty($_POST["name"])) {
        $err .= "Name is not set.";
    } else {
        $name = $_POST["name"];
    }

    if(empty($_POST["start"])) {
        $err .= "Start is not set.";
    } else {
        $start = $_POST["start"];
    }

    if(empty($_POST["end"])) {
        $err .= "End is not set.";
    } else {
        $end = $_POST["end"];
    }

    if(empty($err)) {
        User::eventInsert($name, $start, $end, $_SESSION["calendarId"], $pdo);
    }

    echo $err;
}

?>

<!doctype html>

<html lang="en">
<head>
    <meta charset="utf-8">

    <title>Free calendar</title>
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
            <li><a href="settings.php">Settings</a></li>
            <li><a href="logout.php">Log out</a></li>
        </ul>
    </nav>
</header>
<div class="container">
    <div class="side">
        <div class="user">
            <img class="avatarimg" src="img/<?php echo $_SESSION["avatar"] ?>">
            <p><?php echo $_SESSION["firstname"] . " " . $_SESSION["lastname"] ?></p>
        </div>
        <div class="calendar-list">
            <button type="button" onclick="addCalendar()">Add new calendar</button>
            <ul>
                <?php
                User::echoCalendars($_SESSION["id"], $pdo);
                ?>
            </ul>
        </div>
    </div>
    <div class="calendar-view">
        <div class="controls">
            <div class="left">
                <button>Previous week</button>
                <button>Next week</button>
            </div>
            <div class="right">
                <button type="button"><a href="dashboard.php?deleteCalendar=<?php echo $_SESSION["calendarId"]; ?>">Delete calendar</a></button>
                <button type="button" onclick="addEvent()">Add event</button>
            </div>
        </div>
        <div class="events">
            <?php
            if($_SESSION["calendarId"] != 0) {
                echo "<h2>" . User::calendarNameById($_SESSION["calendarId"], $pdo) . "</h2>";
                User::selectEvents($_SESSION["calendarId"], $pdo);
            }
            ?>
        </div>
    </div>
</div>
<footer>
    <p>2020 Vojtěch Hudec</p>
</footer>

<div id="addCalendar">
    <form action="dashboard.php" method="post">
        <label for="calendarname">Name:</label>
        <input type="text" name="calendarname">
        <label for="expiration">Expiration:</label>
        <input type="checkbox" name="expiration">
        <label for="validUntil">Valid until:</label>
        <input type="date" name="validUntil">
        <input type="submit" value="Add calendar" name="addCalendarButton">
    </form>
</div>

<div id="addEvent">
    <form action="dashboard.php? <?php echo 'id=' . $_SESSION["calendarId"] . '"' ?> method="post">
        <label for="name">Name:</label>
        <input type="text" name="name">
        <label for="start">Start:</label>
        <input type="date" name="start">
        <label for="end">End:</label>
        <input type="date" name="end">
        <input type="submit" value="Add event" name="addEventButton">
    </form>
</div>

<script>
    document.addEventListener('mouseup', function (e) {
        var addCalendar = document.getElementById('addCalendar');
        if (!addCalendar.contains(e.target)) {
            addCalendar.style.display = 'none';
        }

        var addEvent = document.getElementById('addEvent');
        if (!addEvent.contains(e.target)) {
            addEvent.style.display = 'none';
        }
    });

    function addCalendar() {
        document.getElementById("addCalendar").style.display = "block";
    }

    function addEvent() {
        document.getElementById("addEvent").style.display = "block";
    }
</script>
</body>
</html>
