<?php

require_once "DatabaseQueries.php";

class Calendar
{
    public static function getCalendars($userId, $pdo) {
        $arr[][] = NULL;
        $i = 0;
        $query = DatabaseQueries::calendarIdByUserId($userId);
        $stmt = $pdo->query($query);
        while($row = $stmt->fetch()) {
            $arr[$i]["id"] = $row[0];
            $query = DatabaseQueries::calendarNameById($row[0]);
            $stmtCal = $pdo->query($query);
            while($cal = $stmtCal->fetch()) {
                $arr[$i]["name"] = $cal[0];
            }
            $i++;
        }
        return $arr;
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
        $arr[][] = NULL;
        $i = 0;
        $query = DatabaseQueries::selectEvents($calendarId);
        $stmt = $pdo->query($query);

        while($row = $stmt->fetch()) {
            $arr[$i]["name"] = $row["name"];
            $arr[$i]["start"] = $row["start"];
            $arr[$i]["end"] = $row["end"];
            $arr[$i]["id"] = $row["id_event"];
            $i++;
        }

        return $arr;
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