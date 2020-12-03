<?php
/**
 * @var $pdo
 */

session_start();
require "connection.php";
require "User.php";
require "Calendar.php";
require "Category.php";
require "Event.php";

$calendar = new Calendar($pdo);
$event = new Event($pdo);

if(isset($_GET["id"])) {
    $_SESSION["calendarId"] = $_GET["id"];
} else {
    $_SESSION["calendarId"] = 0;
}

if(isset($_GET["deleteEvent"])) {
    $eventId = $_GET["deleteEvent"];
    $event->deleteEvent($eventId);
    header("Location: dashboard.php?id=" . $_SESSION["calendarId"]);
}

if(isset($_GET["deleteCalendar"])) {
    if($_GET["deleteCalendar"] != 0) {
        $calendarId = $_GET["deleteCalendar"];
        $calendar->deleteCalendar($calendarId);
        header("Location: dashboard.php");
    }
}

if(isset($_POST["exportJson"])) {
    if($_GET["id"] != 0) {
        $path = "export-calendar-" . $_SESSION["calendarId"] . ".json";
        $fp = fopen($path, 'w');
        $events = $event->selectEvents($_SESSION["calendarId"]);
        $json = json_encode($events);
        $json = preg_replace('/,\s*"[^"]+":null|"[^"]+":null,?/', '', $json);
        fwrite($fp, $json);
        fclose($fp);
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $path . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: 10');
        readfile($path);
        unlink($path);
        exit;
    }
}

if(isset($_POST["importJson"])) {
    if($_GET["id"] != 0 && !empty($_FILES["fileToUpload"]["name"])) {
        $target_file = basename($_FILES["fileToUpload"]["name"]);
        move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
        $strJsonFileContents = file_get_contents($target_file);
        $array = json_decode($strJsonFileContents, true);
        foreach($array as $item) {
            $event->insertEvent($item["name"], $item["start"], $item["end"], $_SESSION["calendarId"], $item["CATEGORY_id_category"]);
        }
        unlink($target_file);
        header("Location: dashboard.php?id=" . $_SESSION["calendarId"]);
    }
}

if(isset($_POST["addCalendarButton"])) {
    $err = NULL;

    if(empty($_POST["calendarname"])) {
        $err .= "Name is not set.<br>";
    } else {
        $name = $_POST["calendarname"];
    }

    if(!empty($_POST["expiration"])) {
        if (empty($_POST["validUntil"])) {
            $err .= "Valid until is not set.<br>";
        } else {
            $validUntil = $_POST["validUntil"];
        }
    } else {
        $validUntil = NULL;
    }

    if(empty($err)) {
        $calendar->insertCalendar($name, $validUntil);
        header("Location: dashboard.php");
    }
}

if(isset($_POST["addEventButton"])) {
    $err = NULL;
    if($_GET["id"] == 0) {
        $err .= "Calendar is not selected.<br>";
    }

    if(empty($_POST["name"])) {
        $err .= "Name is not set.<br>";
    } else {
        $name = $_POST["name"];
    }

    if(empty($_POST["start"])) {
        $err .= "Start is not set.<br>";
    } else {
        $start = $_POST["start"];
    }

    if(empty($_POST["end"])) {
        $err .= "End is not set.<br>";
    } else {
        $end = $_POST["end"];
    }

    $cat = $_POST["category"];

    if(empty($err)) {
        $category = new Category($pdo);
        $event->insertEvent($name, $start, $end, $_SESSION["calendarId"], $category->getCategoryId($cat));
        header("Location: dashboard.php?id=" . $_SESSION["calendarId"]);
    }

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
            <?php
            if($_SESSION["role"] == "admin") {
                echo '<li><a href="admin.php">Admin</a></li>';
            }
            ?>
            <li><a href="settings.php">Settings</a></li>
            <li><a href="logout.php">Log out</a></li>
        </ul>
    </nav>
</header>
<div class="container">
    <div class="side">
        <div class="user">
            <img class="avatarimg" alt="avatar" src="img/<?php echo $_SESSION["avatar"] ?>">
            <p><?php echo $_SESSION["firstname"] . " " . $_SESSION["lastname"] ?></p>
        </div>
        <div class="calendar-list">
            <button type="button" onclick="addCalendar()">Add new calendar</button>
            <ul>
                <?php
                $arr = $calendar->getCalendarsByUserId($_SESSION["id"]);
                if(!empty($arr[0]["id"])) {
                    foreach($arr as $item) {
                        echo '<li><a href="dashboard.php?id=' . $item["id"] . '">' . $item["name"] . '</a></li>';
                    }
                }
                ?>
            </ul>
        </div>
    </div>
    <div class="calendar-view">
        <div class="controls">
            <div class="left">
                <form action="dashboard.php?id=<?php echo $_SESSION["calendarId"]; ?>" method="post">
                    <input type="submit" value="Export" name="exportJson">
                </form>
                <form action="dashboard.php?id=<?php echo $_SESSION["calendarId"]; ?>" method="post" enctype="multipart/form-data">
                    <input type="submit" value="Import" name="importJson">
                    <input type="file" name="fileToUpload" id="fileToUpload">
                </form>
            </div>
            <div class="right">
                <button type="button"><a href="dashboard.php?deleteCalendar=<?php echo $_SESSION["calendarId"]; ?>">Delete calendar</a></button>
                <button type="button" onclick="addEvent()">Add event</button>
            </div>
        </div>
        <div class="events">
            <?php
            if($_SESSION["calendarId"] != 0) {
                $category = new Category($pdo);
                echo "<h2>" . $calendar->getCalendarName($_SESSION["calendarId"]) . "</h2>";
                echo "<table>";
                echo "<tr>";
                echo "<th>Event</th>";
                echo "<th>Start</th>";
                echo "<th>End</th>";
                echo "<th>Category</th>";
                echo "<th>Action</th>";
                echo "</tr>";

                $arr = $event->selectEvents($_SESSION["calendarId"]);
                if(!empty($arr[0]["name"])) {
                    foreach($arr as $item) {
                        echo "<tr>";
                        echo "<td>" . $item["name"] . "</td><td>" . $item["start"] . "</td><td>" . $item["end"] .
                            "</td><td>" . $category->getCategoryName($item["CATEGORY_id_category"]) . '</td><td><a href="dashboard.php?deleteEvent=' .
                            $item["id"] . '&id=' .
                            $_SESSION["calendarId"] . '">delete</a></td>';
                        echo "</tr>";
                    }
                }
                echo "</table>";
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
    <form action="dashboard.php?<?php echo 'id=' . $_SESSION["calendarId"] . '" ' ?> method="post">
        <label for="name">Name:</label>
        <input type="text" name="name">
        <label for="start">Start:</label>
        <input type="date" name="start">
        <label for="end">End:</label>
        <input type="date" name="end">
        <label for="category">Category:</label>
        <select name="category" id="category">
            <?php
                $category = new Category($pdo);
                $categories = $category->getCategories();
                foreach ($categories as $item) {
                    echo '<option value=' . $item["name"] . '>' . $item["name"] . '</option>';
                }
            ?>
        </select>
        <input type="submit" value="Add event" name="addEventButton">
    </form>
</div>

<?php
if(!empty($err)) {
    echo '<div id="errorAbsolute">
        <p>' . $err . '</p>
    </div>';
}
?>


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

        var errorAbsolute = document.getElementById('errorAbsolute');
        if (!errorAbsolute.contains(e.target)) {
            errorAbsolute.style.display = 'none';
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
