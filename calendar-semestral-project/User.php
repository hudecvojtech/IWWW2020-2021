<?php

require_once "DatabaseQueries.php";

class user
{

    public static function login($email, $password, $pdo)
    {
        $query = DatabaseQueries::userFindByEmail($email);
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $count = $stmt->rowCount();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($count == 1) {
            if (!password_verify($password, $row["password"]))
                return false;

            $_SESSION["id"] = $row["id_user"];
            $_SESSION["email"] = $row["email"];
            $_SESSION["firstname"] = $row["firstname"];
            $_SESSION["lastname"] = $row["lastname"];

            $roleId = $row["ROLE_id_role"];
            $avatarId = $row["AVATAR_id_avatar"];

            $query = DatabaseQueries::roleNameById($roleId);
            $result = $pdo->query($query);
            $result =  $result->fetch();
            $_SESSION["role"] = $result[0];

            $query = DatabaseQueries::avatarPathById($avatarId);
            $result = $pdo->query($query);
            $result =  $result->fetch();
            $_SESSION["avatar"] = $result[0];

            header("location: dashboard.php");
        } else {
            return false;
        }
    }

    public static function register($email, $password, $firstname, $lastname, $roleId, $avatarId, $pdo)
    {
        $query = DatabaseQueries::userFindByEmail($email);
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $count = $stmt->rowCount();
        if ($count == 0) {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $query = DatabaseQueries::userInsert($email, $passwordHash, $firstname, $lastname, $roleId, $avatarId);
            $pdo->query($query);
            User::login($email, $password, $pdo);
        } else {
            return false;
        }
    }

    public static function echoCalendars($id, $pdo) {
        $query = DatabaseQueries::calendarIdByUserId($id);
        $stmt = $pdo->query($query);
        while($row = $stmt->fetch()) {
            $query = DatabaseQueries::calendarNameById($row[0]);
            $stmtCal = $pdo->query($query);
            while($cal = $stmtCal->fetch()) {
                echo '<li><a href="dashboard.php?id=' . $row[0] . '">' . $cal[0] . '</a></li>';
            }
        }
    }

    public static function calendarIdByName($name, $pdo) {
        $query = DatabaseQueries::calendarIdByName($name);
        $stmt = $pdo->query($query);
        $count = $stmt->rowCount();
        if($count == 0) return 0;

        $stmt = $stmt->fetch();
        return $stmt[0];
    }

    public static function calendarNameById($id, $pdo) {
        $query = DatabaseQueries::calendarNameById($id);
        $stmt = $pdo->query($query);
        $stmt = $stmt->fetch();
        return $stmt[0];
    }

    public static function insertUserIdCalendarId($userId, $calendarId, $pdo) {
        $query = DatabaseQueries::insertUserIdCalendarId($userId, $calendarId);
        $pdo->query($query);
    }

    public static function calendarInsert($name, $validUntil, $pdo) {
        $query = DatabaseQueries::insertCalendar($name, $validUntil);
        $statement = $pdo->prepare($query);
        if(!$statement->execute()) {
            echo $statement->errorInfo();
        } else {
            self::insertUserIdCalendarId($_SESSION["id"], self::calendarIdByName($name, $pdo), $pdo);
        }
    }

    public static function eventInsert($name, $start, $end, $calendarId, $pdo) {
        $query = DatabaseQueries::insertEvent($name, $start, $end, $calendarId);
        $pdo->query($query);
    }

    public static function selectEvents($calendarId, $pdo) {
        $query = DatabaseQueries::selectEvents($calendarId);
        $stmt = $pdo->query($query);
        while($row = $stmt->fetch()) {
            echo $row["name"] . ": " . $row["start"] . " - " . $row["end"] . ' - <a href="dashboard.php?deleteEvent=' . $row["id_event"] . '&id=' . $_SESSION["calendarId"] . '">delete</a>';
        }
    }

    public static function deleteEvent($eventId, $pdo)
    {
        $query = DatabaseQueries::deleteEvent($eventId);
        $pdo->query($query);
    }

    public static function deleteCalendar($calendarId, $pdo)
    {
        $query = DatabaseQueries::deleteCalendar($calendarId);
        $pdo->query($query);
    }
}